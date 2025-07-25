<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\ObjectDeclarations;

/**
 * Class WordPressVIPMinimum_Sniffs_Classes_DeclarationCompatibilitySniff
 */
class DeclarationCompatibilitySniff implements Sniff {

	/**
	 * A list of classes and methods to check.
	 *
	 * @deprecated 3.1.0 This should never have been a public property.
	 *
	 * @var array<string, array<string, array<int|string, string|array<string, bool|string>>>>
	 */
	public $checkClasses = [];

	/**
	 * List of grouped classes with same methods (as they extend the same parent class).
	 *
	 * @deprecated 3.1.0 This should never have been a public property.
	 *
	 * @var array<string, string[]>
	 */
	public $checkClassesGroups = [];

	/**
	 * A list of classes and information on the methods to check for those classes.
	 *
	 * @var array<string, array<string, array<string, array<string, bool|string>>>>
	 */
	private $methodSignatures = [
		'WP_Widget' => [
			'widget'                => [
				'args'     => [],
				'instance' => [],
			],
			'update'                => [
				'new_instance' => [],
				'old_instance' => [],
			],
			'form'                  => [
				'instance' => [],
			],
			'WP_Widget'             => [
				'id_base'         => [],
				'name'            => [],
				'widget_options'  => [
					'default' => 'array()',
				],
				'control_options' => [
					'default' => 'array()',
				],
			],
			'get_field_name'        => [
				'field_name' => [],
			],
			'get_field_id'          => [
				'field_name' => [],
			],
			'_register'             => [],
			'_set'                  => [
				'number' => [],
			],
			'_get_display_callback' => [],
			'_get_update_callback'  => [],
			'_get_form_callback'    => [],
			'is_preview'            => [],
			'display_callback'      => [
				'args'        => [],
				'widget_args' => [
					'default' => '1',
				],
			],
			'update_callback'       => [
				'deprecated' => [
					'default' => '1',
				],
			],
			'form_callback'         => [
				'widget_args' => [
					'default' => '1',
				],
			],
			'_register_one'         => [
				'number' => [
					'default' => '-1',
				],
			],
			'save_settings'         => [
				'settings' => [],
			],
			'get_settings'          => [],
		],

		'Walker'    => [
			'start_lvl'                   => [
				'output' => [
					'pass_by_reference' => true,
				],
				'depth'  => [
					'default' => '0',
				],
				'args'   => [
					'default' => 'array()',
				],
			],
			'end_lvl'                     => [
				'output' => [
					'pass_by_reference' => true,
				],
				'depth'  => [
					'default' => '0',
				],
				'args'   => [
					'default' => 'array()',
				],
			],
			'start_el'                    => [
				'output'            => [
					'pass_by_reference' => true,
				],
				'data_object'       => [],
				'depth'             => [
					'default' => '0',
				],
				'args'              => [
					'default' => 'array()',
				],
				'current_object_id' => [
					'default' => '0',
				],
			],
			'end_el'                      => [
				'output'      => [
					'pass_by_reference' => true,
				],
				'data_object' => [],
				'depth'       => [
					'default' => '0',
				],
				'args'        => [
					'default' => 'array()',
				],
			],
			'display_element'             => [
				'element'           => [],
				'children_elements' => [
					'pass_by_reference' => true,
				],
				'max_depth'         => [],
				'depth'             => [],
				'args'              => [],
				'output'            => [
					'pass_by_reference' => true,
				],
			],
			'walk'                        => [
				'elements'  => [],
				'max_depth' => [],
				'args'      => [
					'variable_length' => true,
				],
			],
			'paged_walk'                  => [
				'elements'  => [],
				'max_depth' => [],
				'page_num'  => [],
				'per_page'  => [],
				'args'      => [
					'variable_length' => true,
				],
			],
			'get_number_of_root_elements' => [
				'elements' => [],
			],
			'unset_children'              => [
				'element'           => [],
				'children_elements' => [
					'pass_by_reference' => true,
				],
			],
		],
	];

	/**
	 * Classes this sniff checks for being extended.
	 *
	 * @var array<string, string> Key is the name of a potentially extended class,
	 *                            value the canonical name for the method signatures definition.
	 */
	private $extendedClassToSignatures = [
		'WP_Widget'                 => 'WP_Widget',
		'Walker'                    => 'Walker',
		'Walker_Category_Checklist' => 'Walker',
		'Walker_Category'           => 'Walker',
		'Walker_CategoryDropdown'   => 'Walker',
		'Walker_PageDropdown'       => 'Walker',
		'Walker_Nav_Menu'           => 'Walker',
		'Walker_Page'               => 'Walker',
		'Walker_Comment'            => 'Walker',
	];

	/**
	 * Translate from case-insensitive names to proper case method names.
	 *
	 * @var array<string, array<string, string>> Primary key is the class name in proper case.
	 *                                           Value is an array with method names in lowercase as keys
	 *                                           and these same method names in proper case as values.
	 */
	private $methodToProperCase = [];

	/**
	 * Translate from case-insensitive names to proper case class names.
	 *
	 * @var array<string, string> Key is the lowercase name of a class, value the proper case.
	 */
	private $classToProperCase = [];

	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * @return array<int|string>
	 */
	public function register() {
		// Lowercase all names to allow for correct comparisons, as PHP treats class/function names case-insensitively.
		// But also store translation tables to be able to get the proper case.
		foreach ( $this->methodSignatures as $key => $value ) {
			$methodNames                      = array_keys( $value );
			$this->methodToProperCase[ $key ] = array_change_key_case( array_combine( $methodNames, $methodNames ), CASE_LOWER );

			$this->methodSignatures[ $key ] = array_change_key_case( $value, CASE_LOWER );
		}

		$classNames                      = array_keys( $this->extendedClassToSignatures );
		$this->classToProperCase         = array_change_key_case( array_combine( $classNames, $classNames ), CASE_LOWER );
		$this->extendedClassToSignatures = array_change_key_case( $this->extendedClassToSignatures, CASE_LOWER );

		return [
			T_CLASS,
			T_ANON_CLASS,
		];
	}

	/**
	 * Processes the tokens that this sniff is interested in.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current token
	 *                                               in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$parentClassName = ObjectDeclarations::findExtendedClassName( $phpcsFile, $stackPtr );
		if ( $parentClassName === false ) {
			// This class does not extend any other class.
			return;
		}

		$parentClassNameLC = ltrim( strtolower( $parentClassName ), '\\' ); // Trim off potential FQN indicator.
		if ( isset( $this->extendedClassToSignatures[ $parentClassNameLC ] ) === false ) {
			// This class does not extend a class we are interested in.
			return;
		}

		// Store the originalParentClassName since we might override the parentClassName due to signature notations grouping.
		$originalParentClassNamePC = $this->classToProperCase[ $parentClassNameLC ];
		$parentClassName           = $this->extendedClassToSignatures[ $parentClassNameLC ];

		$methods = ObjectDeclarations::getDeclaredMethods( $phpcsFile, $stackPtr );
		if ( empty( $methods ) ) {
			return;
		}

		foreach ( $methods as $methodName => $functionPtr ) {
			$methodNameLC = strtolower( $methodName );
			if ( isset( $this->methodSignatures[ $parentClassName ][ $methodNameLC ] ) === false ) {
				// This method is not one we are interested in.
				continue;
			}

			$methodNamePC = $this->methodToProperCase[ $parentClassName ][ $methodNameLC ];

			$childParams     = FunctionDeclarations::getParameters( $phpcsFile, $functionPtr );
			$childParamCount = count( $childParams );

			$parentParams     = $this->methodSignatures[ $parentClassName ][ $methodNameLC ];
			$parentParamCount = count( $parentParams );

			/*
			 * If there are parameters, verify if the last parameter of both the parent and the child are variadic.
			 * Only the last parameter can be variadic and if the parent has this, the child must also,
			 * independently of potential extra optional parameters having been inserted before that last parameter.
			 *
			 * Also note that a child can make the last parameter variadic, even if the parent parameter was not.
			 * This will no longer trigger a warning since PHP 8.0.
			 */
			if ( $childParamCount > 0 && $parentParamCount > 0 ) {
				$childLastParam  = $childParams[ $childParamCount - 1 ];
				$parentLastParam = $parentParams[ array_keys( $parentParams )[ $parentParamCount - 1 ] ];

				if ( ( isset( $parentLastParam['variable_length'] ) === true && $parentLastParam['variable_length'] === true )
					&& $childLastParam['variable_length'] !== true
				) {
					$this->addError( $phpcsFile, $functionPtr, $stackPtr, $originalParentClassNamePC, $methodNamePC, $childParams, $parentParams );
					continue;
				}
			}

			if ( $childParamCount > 0 ) {
				// Check that no other parameters in the child signature are declared as variadic.
				for ( $i = 0; $i < ( $childParamCount - 1 ); $i++ ) {
					if ( $childParams[ $i ]['variable_length'] === true ) {
						$this->addError( $phpcsFile, $functionPtr, $stackPtr, $originalParentClassNamePC, $methodNamePC, $childParams, $parentParams );
						continue 2;
					}
				}
			}

			if ( $childParamCount > $parentParamCount ) {
				$extra_params                  = array_slice( $childParams, $parentParamCount - $childParamCount );
				$all_extra_params_have_default = true;
				foreach ( $extra_params as $extra_param ) {
					if ( isset( $extra_param['default'] ) === false
						&& $extra_param['variable_length'] === false
					) {
						$all_extra_params_have_default = false;
						break;
					}
				}

				if ( $all_extra_params_have_default === false ) {
					$this->addError( $phpcsFile, $functionPtr, $stackPtr, $originalParentClassNamePC, $methodNamePC, $childParams, $parentParams );
					continue;
				}
			} elseif ( $childParamCount !== $parentParamCount ) {
				$this->addError( $phpcsFile, $functionPtr, $stackPtr, $originalParentClassNamePC, $methodNamePC, $childParams, $parentParams );
				continue;
			}

			$i = 0;
			foreach ( $parentParams as $param ) {
				if (
					(
						isset( $param['default'] ) === true
						&& isset( $childParams[ $i ]['default'] ) === false
						&& $childParams[ $i ]['variable_length'] === false
					) || (
						// Parameter in parent class has reference, child does not.
						isset( $param['pass_by_reference'] ) === true
						&& $param['pass_by_reference'] !== $childParams[ $i ]['pass_by_reference']
					) || (
						// Parameter in parent class does *not* have reference, child does.
						( isset( $param['pass_by_reference'] ) === false
						|| $param['pass_by_reference'] === false )
						&& $childParams[ $i ]['pass_by_reference'] === true
					)
				) {
					$this->addError( $phpcsFile, $functionPtr, $stackPtr, $originalParentClassNamePC, $methodNamePC, $childParams, $parentParams );
					continue 2;
				}
				++$i;
			}
		}
	}

	/**
	 * Generates an error with nice current and parent class method notations.
	 *
	 * @param File                                      $phpcsFile              The PHP_CodeSniffer file where the token was found.
	 * @param int                                       $stackPtr               The position of the current T_FUNCTION token in the stack.
	 * @param int                                       $currScope              A pointer to the start of the OO scope.
	 * @param string                                    $parentClassName        The name of the extended (parent) class.
	 * @param string                                    $methodName             The name of the method currently being examined.
	 * @param array<int, array<string, mixed>>          $currentMethodSignature The list of params and their options of the method
	 *                                                                          which is being examined.
	 * @param array<string, array<string, bool|string>> $parentMethodSignature  The list of params and their options of the parent class method.
	 *
	 * @return void
	 */
	private function addError( File $phpcsFile, $stackPtr, $currScope, $parentClassName, $methodName, $currentMethodSignature, $parentMethodSignature ) {
		$tokens           = $phpcsFile->getTokens();
		$currentClassName = '[AnonymousClass]';
		if ( $tokens[ $currScope ]['code'] !== T_ANON_CLASS ) {
			$currentClassName = ObjectDeclarations::getName( $phpcsFile, $currScope );
		}

		$currentSignature = implode( ', ', $this->generateParamList( $currentMethodSignature ) );
		$currentSignature = sprintf( '%s::%s(%s)', $currentClassName, $methodName, $currentSignature );

		$parentSignature = implode( ', ', $this->generateParamList( $parentMethodSignature ) );
		$parentSignature = sprintf( '%s::%s(%s)', $parentClassName, $methodName, $parentSignature );

		$message = 'Declaration of `%s` should be compatible with `%s`.';
		$data    = [ $currentSignature, $parentSignature ];
		$phpcsFile->addError( $message, $stackPtr, 'DeclarationCompatibility', $data );
	}

	/**
	 * Generates an array of params as they appear in the signature.
	 *
	 * @param array $methodSignature Signature of a method.
	 *
	 * @return array<string>
	 */
	private function generateParamList( $methodSignature ) {
		$paramList = [];
		foreach ( $methodSignature as $param => $options ) {
			$paramName = '$';
			if ( empty( $options ) === true ) {
				$paramList[] = '$' . $param;
				continue;
			}

			if ( isset( $options['name'] ) === true ) {
				$paramName = $options['name'];
			} else {
				$paramName .= $param;
			}

			if ( isset( $options['variable_length'] ) === true && $options['variable_length'] === true ) {
				$paramName = '...' . $paramName;
			}

			if ( isset( $options['pass_by_reference'] ) === true && $options['pass_by_reference'] === true ) {
				$paramName = '&' . $paramName;
			}

			if ( isset( $options['default'] ) === true && empty( $options['default'] ) === false ) {
				$paramName .= ' = ' . trim( $options['default'] );
			}

			$paramList[] = $paramName;
		}

		return $paramList;
	}
}

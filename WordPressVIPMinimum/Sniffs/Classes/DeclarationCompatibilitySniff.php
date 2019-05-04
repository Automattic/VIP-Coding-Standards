<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\AbstractScopeSniff;

/**
 * Class WordPressVIPMinimum_Sniffs_Classes_DeclarationCompatibilitySniff
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class DeclarationCompatibilitySniff extends AbstractScopeSniff {

	/**
	 * The name of the class we are currently checking.
	 *
	 * @var string
	 */
	private $currentClass = '';

	/**
	 * A list of functions in the current class.
	 *
	 * @var string[]
	 */
	private $functionList = [];

	/**
	 * A list of classes and methods to check.
	 *
	 * @var string[]
	 */
	public $checkClasses = [
		'WP_Widget' => [
			'widget'                => [ 'args', 'instance' ],
			'update'                => [ 'new_instance', 'old_instance' ],
			'form'                  => [ 'instance' ],
			'WP_Widget'             => [
				'id_base',
				'name',
				'widget_options'  => [
					'default' => 'array()',
				],
				'constol_options' => [
					'default' => 'array()',
				],
			],
			'get_field_name'        => [ 'field_name' ],
			'get_field_id'          => [ 'field_name' ],
			'_register'             => [],
			'_set'                  => [ 'number' ],
			'_get_display_callback' => [],
			'_get_update_callback'  => [],
			'_get_form_callback'    => [],
			'is_preview'            => [],
			'display_callback'      => [
				'args',
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
			'register_one'          => [
				'number' => [
					'default' => '-1',
				],
			],
			'save_settings'         => [ 'settings' ],
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
				'object',
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
				'output' => [
					'pass_by_reference' => true,
				],
				'object',
				'depth'  => [
					'default' => '0',
				],
				'args'   => [
					'default' => 'array()',
				],
			],
			'display_element'             => [
				'element',
				'children_elements' => [
					'pass_by_reference' => true,
				],
				'max_depth',
				'depth',
				'args',
				'output'            => [
					'pass_by_reference' => true,
				],
			],
			'walk'                        => [
				'elements',
				'max_depth',
			],
			'paged_walk'                  => [
				'elements',
				'max_depth',
				'page_num',
				'per_page',
			],
			'get_number_of_root_elements' => [
				'elements',
			],
			'unset_children'              => [
				'el',
				'children_elements' => [
					'pass_by_reference' => true,
				],
			],
		],
	];

	/**
	 * List of grouped classes with same methods (as they extend the same parent class)
	 *
	 * @var string[]
	 */
	public $checkClassesGroups = [
		'Walker' => [
			'Walker_Category_Checklist',
			'Walker_Category',
			'Walker_CategoryDropdown',
			'Walker_PageDropdown',
			'Walker_Nav_Menu',
			'Walker_Page',
			'Walker_Comment',
		],
	];

	/**
	 * Constructs the test with the tokens it wishes to listen for.
	 */
	public function __construct() {
		parent::__construct( [ T_CLASS ], [ T_FUNCTION ], true );
	}

	/**
	 * Processes this test when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 * @param int  $currScope A pointer to the start of the scope.
	 *
	 * @return void
	 */
	protected function processTokenWithinScope( File $phpcsFile, $stackPtr, $currScope ) {

		$className = $phpcsFile->getDeclarationName( $currScope );

		if ( $className !== $this->currentClass ) {
			$this->loadFunctionNamesInScope( $phpcsFile, $currScope );
			$this->currentClass = $className;
		}

		$methodName = $phpcsFile->getDeclarationName( $stackPtr );

		$parentClassName = $phpcsFile->findExtendedClassName( $currScope );
		if ( false === $parentClassName ) {
			// This class does not extend any other class.
			return;
		}

		// Meed to define the originalParentClassName since we might override the parentClassName due to signature notations grouping.
		$originalParentClassName = $parentClassName;

		if ( false === array_key_exists( $parentClassName, $this->checkClasses ) ) {
			// This class does not extend a class we are interested in.
			foreach ( $this->checkClassesGroups as $parent => $children ) {
				// But it might be one of the grouped classes.
				foreach ( $children as $child ) {
					if ( $child === $parentClassName ) {
						$parentClassName = $parent;
						break 2;
					}
				}
			}
			if ( false === array_key_exists( $parentClassName, $this->checkClasses ) ) {
				// This class really does not extend a class we are interested in.
				return;
			}
		}

		if ( false === array_key_exists( $methodName, $this->checkClasses[ $parentClassName ] ) &&
			false === in_array( $methodName, $this->checkClasses[ $parentClassName ], true )
		) {
			// This method is not a one we are interested in.
			return;
		}

		$signatureParams = $phpcsFile->getMethodParameters( $stackPtr );

		$parentSignature = $this->checkClasses[ $parentClassName ][ $methodName ];

		if ( count( $signatureParams ) > count( $parentSignature ) ) {
			$extra_params                  = array_slice( $signatureParams, count( $parentSignature ) - count( $signatureParams ) );
			$all_extra_params_have_default = true;
			foreach ( $extra_params as $extra_param ) {
				if ( false === array_key_exists( 'default', $extra_param ) || 'true' !== $extra_param['default'] ) {
					$all_extra_params_have_default = false;
				}
			}
			if ( true === $all_extra_params_have_default ) {
				return; // We're good.
			}
		}

		if ( count( $signatureParams ) !== count( $parentSignature ) ) {
			$this->addError( $originalParentClassName, $methodName, $signatureParams, $parentSignature, $phpcsFile, $stackPtr );
			return;
		}

		$i = 0;
		foreach ( $parentSignature as $key => $param ) {
			if ( true === is_array( $param ) ) {
				if (
					(
						true === array_key_exists( 'default', $param ) &&
						false === array_key_exists( 'default', $signatureParams[ $i ] )
					) || (
						true === array_key_exists( 'pass_by_reference', $param ) &&
						$param['pass_by_reference'] !== $signatureParams[ $i ]['pass_by_reference']
					)
				) {
					$this->addError( $originalParentClassName, $methodName, $signatureParams, $parentSignature, $phpcsFile, $stackPtr );
					return;
				}
			}
			$i++;
		}
	}

	/**
	 * Generates an error with nice current and parent class method notations
	 *
	 * @param string $parentClassName        The name of the extended (parent) class.
	 * @param string $methodName             The name of the method currently being examined.
	 * @param array  $currentMethodSignature The list of params and their options of the method which is being examined.
	 * @param array  $parentMethodSignature  The list of params and their options of the parent class method.
	 * @param File   $phpcsFile              The PHP_CodeSniffer file where the token was found.
	 * @param int    $stackPtr               The position of the current token in the stack.
	 *
	 * @return void
	 */
	private function addError( $parentClassName, $methodName, $currentMethodSignature, $parentMethodSignature, $phpcsFile, $stackPtr ) {

		$currentSignature = sprintf( '%s::%s(%s)', $this->currentClass, $methodName, implode( ', ', $this->generateParamList( $currentMethodSignature ) ) );

		$parentSignature = sprintf( '%s::%s(%s)', $parentClassName, $methodName, implode( ', ', $this->generateParamList( $parentMethodSignature ) ) );

		$message = 'Declaration of `%s` should be compatible with `%s`.';
		$data    = [ $currentSignature, $parentSignature ];
		$phpcsFile->addError( $message, $stackPtr, 'DeclarationCompatibility', $data );
	}

	/**
	 * Generates an array of params as they appear in the signature.
	 *
	 * @param array $methodSignature Signature of a method.
	 *
	 * @return array
	 */
	private function generateParamList( $methodSignature ) {
		$paramList = [];
		foreach ( $methodSignature as $param => $options ) {
			$paramName = '$';
			if ( false === is_array( $options ) ) {
				$paramList[] = '$' . $options;
				continue;
			}

			if ( true === array_key_exists( 'name', $options ) ) {
				$paramName = $options['name'];
			} else {
				$paramName .= $param;
			}

			if ( true === array_key_exists( 'pass_by_reference', $options ) && true === $options['pass_by_reference'] ) {
				$paramName = '&' . $paramName;
			}

			if ( true === array_key_exists( 'default', $options ) && false === empty( $options['default'] ) ) {
				$paramName .= ' = ' . trim( $options['default'] );
			}

			$paramList[] = $paramName;
		}

		return $paramList;
	}

	/**
	 * Extracts all the function names found in the given scope.
	 *
	 * @param File $phpcsFile The current file being scanned.
	 * @param int  $currScope A pointer to the start of the scope.
	 *
	 * @return void
	 */
	protected function loadFunctionNamesInScope( File $phpcsFile, $currScope ) {
		$this->functionList = [];
		$tokens             = $phpcsFile->getTokens();

		for ( $i = ( $tokens[ $currScope ]['scope_opener'] + 1 ); $i < $tokens[ $currScope ]['scope_closer']; $i++ ) {
			if ( T_FUNCTION !== $tokens[ $i ]['code'] ) {
				continue;
			}

			$next                 = $phpcsFile->findNext( T_STRING, $i );
			$this->functionList[] = trim( $tokens[ $next ]['content'] );
		}
	}

	/**
	 * Do nothing outside the scope. Has to be implemented accordingly to parent abstract class.
	 *
	 * @param File $phpcsFile PHPCS File.
	 * @param int  $stackPtr  Stack position.
	 */
	public function processTokenOutsideScope( File $phpcsFile, $stackPtr ) {}
}

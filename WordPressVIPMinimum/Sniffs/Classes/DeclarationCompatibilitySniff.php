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
use PHP_CodeSniffer\Sniffs\AbstractScopeSniff;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\ObjectDeclarations;

/**
 * Class WordPressVIPMinimum_Sniffs_Classes_DeclarationCompatibilitySniff
 */
class DeclarationCompatibilitySniff extends AbstractScopeSniff {

	/**
	 * A list of classes and methods to check.
	 *
	 * @var array<string, array<string, array<int|string, string|array<string, bool|string>>>>
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
				'control_options' => [
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
			'_register_one'         => [
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
				'data_object',
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
				'data_object',
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
				'args' => [
					'variable_length' => true,
				],
			],
			'paged_walk'                  => [
				'elements',
				'max_depth',
				'page_num',
				'per_page',
				'args' => [
					'variable_length' => true,
				],
			],
			'get_number_of_root_elements' => [
				'elements',
			],
			'unset_children'              => [
				'element',
				'children_elements' => [
					'pass_by_reference' => true,
				],
			],
		],
	];

	/**
	 * List of grouped classes with same methods (as they extend the same parent class)
	 *
	 * @var array<string, string[]>
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
		parent::__construct( [ T_CLASS ], [ T_FUNCTION ], false );
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

		$methodName = FunctionDeclarations::getName( $phpcsFile, $stackPtr );

		$parentClassName = ObjectDeclarations::findExtendedClassName( $phpcsFile, $currScope );
		if ( $parentClassName === false ) {
			// This class does not extend any other class.
			return;
		}

		// Need to define the originalParentClassName since we might override the parentClassName due to signature notations grouping.
		$originalParentClassName = $parentClassName;

		if ( array_key_exists( $parentClassName, $this->checkClasses ) === false ) {
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
			if ( array_key_exists( $parentClassName, $this->checkClasses ) === false ) {
				// This class really does not extend a class we are interested in.
				return;
			}
		}

		if ( array_key_exists( $methodName, $this->checkClasses[ $parentClassName ] ) === false &&
			in_array( $methodName, $this->checkClasses[ $parentClassName ], true ) === false
		) {
			// This method is not a one we are interested in.
			return;
		}

		$signatureParams = FunctionDeclarations::getParameters( $phpcsFile, $stackPtr );

		$parentSignature = $this->checkClasses[ $parentClassName ][ $methodName ];

		if ( count( $signatureParams ) > count( $parentSignature ) ) {
			$extra_params                  = array_slice( $signatureParams, count( $parentSignature ) - count( $signatureParams ) );
			$all_extra_params_have_default = true;
			foreach ( $extra_params as $extra_param ) {
				if ( array_key_exists( 'default', $extra_param ) === false || $extra_param['default'] !== 'true' ) {
					$all_extra_params_have_default = false;
				}
			}
			if ( $all_extra_params_have_default === true ) {
				return; // We're good.
			}
		}

		if ( count( $signatureParams ) !== count( $parentSignature ) ) {
			$this->addError( $phpcsFile, $stackPtr, $currScope, $originalParentClassName, $methodName, $signatureParams, $parentSignature );
			return;
		}

		$i = 0;
		foreach ( $parentSignature as $key => $param ) {
			if ( is_array( $param ) === true ) {
				if (
					(
						array_key_exists( 'default', $param ) === true &&
						array_key_exists( 'default', $signatureParams[ $i ] ) === false
					) || (
						array_key_exists( 'pass_by_reference', $param ) === true &&
						$param['pass_by_reference'] !== $signatureParams[ $i ]['pass_by_reference']
					) || (
						array_key_exists( 'variable_length', $param ) === true &&
						$param['variable_length'] !== $signatureParams[ $i ]['variable_length']
					)
				) {
					$this->addError( $phpcsFile, $stackPtr, $currScope, $originalParentClassName, $methodName, $signatureParams, $parentSignature );
					return;
				}
			}
			++$i;
		}
	}

	/**
	 * Generates an error with nice current and parent class method notations
	 *
	 * @param File   $phpcsFile              The PHP_CodeSniffer file where the token was found.
	 * @param int    $stackPtr               The position of the current token in the stack.
	 * @param int    $currScope              A pointer to the start of the scope.
	 * @param string $parentClassName        The name of the extended (parent) class.
	 * @param string $methodName             The name of the method currently being examined.
	 * @param array  $currentMethodSignature The list of params and their options of the method which is being examined.
	 * @param array  $parentMethodSignature  The list of params and their options of the parent class method.
	 *
	 * @return void
	 */
	private function addError( File $phpcsFile, $stackPtr, $currScope, $parentClassName, $methodName, $currentMethodSignature, $parentMethodSignature ) {
		$currentClassName = ObjectDeclarations::getName( $phpcsFile, $currScope );

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
			if ( is_array( $options ) === false ) {
				$paramList[] = '$' . $options;
				continue;
			}

			if ( array_key_exists( 'name', $options ) === true ) {
				$paramName = $options['name'];
			} else {
				$paramName .= $param;
			}

			if ( array_key_exists( 'variable_length', $options ) === true && $options['variable_length'] === true ) {
				$paramName = '...' . $paramName;
			}

			if ( array_key_exists( 'pass_by_reference', $options ) === true && $options['pass_by_reference'] === true ) {
				$paramName = '&' . $paramName;
			}

			if ( array_key_exists( 'default', $options ) === true && empty( $options['default'] ) === false ) {
				$paramName .= ' = ' . trim( $options['default'] );
			}

			$paramList[] = $paramName;
		}

		return $paramList;
	}

	/**
	 * Do nothing outside the scope. Has to be implemented accordingly to parent abstract class.
	 *
	 * @param File $phpcsFile PHPCS File.
	 * @param int  $stackPtr  Stack position.
	 */
	public function processTokenOutsideScope( File $phpcsFile, $stackPtr ) {}
}

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
	private $_currentClass = '';

	/**
	 * A list of functions in the current class.
	 *
	 * @var string[]
	 */
	private $_functionList = array();

	/**
	 * A list of classes and methods to check.
	 *
	 * @var array
	 */
	public $checkClasses = array(
		'WP_Widget' => array(
			'widget'                => array( 'args', 'instance' ),
			'update'                => array( 'new_instance', 'old_instance' ),
			'form'                  => array( 'instance' ),
			'WP_Widget'             => array(
				'id_base',
				'name',
				'widget_options'  => array(
					'default' => 'array()',
				),
				'constol_options' => array(
					'default' => 'array()',
				),
			),
			'get_field_name'        => array( 'field_name' ),
			'get_field_id'          => array( 'field_name' ),
			'_register'             => array(),
			'_set'                  => array( 'number' ),
			'_get_display_callback' => array(),
			'_get_update_callback'  => array(),
			'_get_form_callback'    => array(),
			'is_preview'            => array(),
			'display_callback'      => array(
				'args',
				'widget_args' => array(
					'default' => '1',
				),
			),
			'update_callback'       => array(
				'deprecated' => array(
					'default' => '1',
				),
			),
			'form_callback'         => array(
				'widget_args' => array(
					'default' => '1',
				),
			),
			'register_one'          => array(
				'number' => array(
					'default' => '-1',
				),
			),
			'save_settings'         => array( 'settings' ),
			'get_settings'          => array(),
		),
		'Walker'    => array(
			'start_lvl'                   => array(
				'output' => array(
					'pass_by_reference' => true,
				),
				'depth'  => array(
					'default' => '0',
				),
				'args'   => array(
					'default' => 'array()',
				),
			),
			'end_lvl'                     => array(
				'output' => array(
					'pass_by_reference' => true,
				),
				'depth'  => array(
					'default' => '0',
				),
				'args'   => array(
					'default' => 'array()',
				),
			),
			'start_el'                    => array(
				'output'            => array(
					'pass_by_reference' => true,
				),
				'object',
				'depth'             => array(
					'default' => '0',
				),
				'args'              => array(
					'default' => 'array()',
				),
				'current_object_id' => array(
					'default' => '0',
				),
			),
			'end_el'                      => array(
				'output' => array(
					'pass_by_reference' => true,
				),
				'object',
				'depth'  => array(
					'default' => '0',
				),
				'args'   => array(
					'default' => 'array()',
				),
			),
			'display_element'             => array(
				'element',
				'children_elements' => array(
					'pass_by_reference' => true,
				),
				'max_depth',
				'depth',
				'args',
				'output'            => array(
					'pass_by_reference' => true,
				),
			),
			'walk'                        => array(
				'elements',
				'max_depth',
			),
			'paged_walk'                  => array(
				'elements',
				'max_depth',
				'page_num',
				'per_page',
			),
			'get_number_of_root_elements' => array(
				'elements',
			),
			'unset_children'              => array(
				'el',
				'children_elements' => array(
					'pass_by_reference' => true,
				),
			),
		),
	);

	/**
	 * List of grouped classes with same methods (as they extend the same parent class)
	 *
	 * @var array
	 */
	public $checkClassesGroups = array(
		'Walker' => array(
			'Walker_Category_Checklist',
			'Walker_Category',
			'Walker_CategoryDropdown',
			'Walker_PageDropdown',
			'Walker_Nav_Menu',
			'Walker_Page',
			'Walker_Comment',
		),
	);

	/**
	 * Constructs the test with the tokens it wishes to listen for.
	 */
	public function __construct() {
		parent::__construct( array( T_CLASS ), array( T_FUNCTION ), true );
	}//end __construct()

	/**
	 * Processes this test when one of its tokens is encountered.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The current file being scanned.
	 * @param int                         $stackPtr  The position of the current token
	 *                                               in the stack passed in $tokens.
	 * @param int                         $currScope A pointer to the start of the scope.
	 *
	 * @return void
	 */
	protected function processTokenWithinScope( File $phpcsFile, $stackPtr, $currScope ) {

		$className = $phpcsFile->getDeclarationName( $currScope );

		if ( $className !== $this->_currentClass ) {
			$this->loadFunctionNamesInScope( $phpcsFile, $currScope );
			$this->_currentClass = $className;
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
			$extra_params                  = array_slice( $signatureParams, ( count( $parentSignature ) - count( $signatureParams ) ) );
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
				if ( true === array_key_exists( 'default', $param ) && false === array_key_exists( 'default', $signatureParams[ $i ] )
					|| true === array_key_exists( 'pass_by_reference', $param ) && $param['pass_by_reference'] !== $signatureParams[ $i ]['pass_by_reference']
				) {
					$this->addError( $originalParentClassName, $methodName, $signatureParams, $parentSignature, $phpcsFile, $stackPtr );
					return;
				}
			}
			$i++;
		}
	}//end processTokenWithinScope()

	/**
	 * Generates an error with nice current and parent class method notations
	 *
	 * @param string                      $parentClassName        The name of the extended (parent) class.
	 * @param string                      $methodName             The name of the method currently being examined.
	 * @param array                       $currentMethodSignature The list of params and their options of the method which is being examined.
	 * @param array                       $parentMethodSignature  The list of params and their options of the parent class method.
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile              The file being scanned.
	 * @param int                         $stackPtr               The position of the current token in the stack.
	 *
	 * @return void
	 */
	private function addError( $parentClassName, $methodName, $currentMethodSignature, $parentMethodSignature, $phpcsFile, $stackPtr ) {

		$currentSignature = sprintf( '%s::%s(%s)', $this->_currentClass, $methodName, implode( ', ', $this->generateParamList( $currentMethodSignature ) ) );

		$parentSignature = sprintf( '%s::%s(%s)', $parentClassName, $methodName, implode( ', ', $this->generateParamList( $parentMethodSignature ) ) );

		$phpcsFile->addError( sprintf( 'Declaration of `%s` should be compatible with `%s`', $currentSignature, $parentSignature ), $stackPtr, 'DeclarationCompatibility' );
	}//end addError()

	/**
	 * Generates an array of params as they appear in the signature.
	 *
	 * @param array $methodSignature Signature of a method.
	 *
	 * @return array
	 */
	private function generateParamList( $methodSignature ) {
		$paramList = array();
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
	}//end generateParamList()

	/**
	 * Extracts all the function names found in the given scope.
	 *
	 * @param File $phpcsFile The current file being scanned.
	 * @param int  $currScope A pointer to the start of the scope.
	 *
	 * @return void
	 */
	protected function loadFunctionNamesInScope( File $phpcsFile, $currScope ) {
		$this->_functionList = array();
		$tokens              = $phpcsFile->getTokens();

		for ( $i = ( $tokens[ $currScope ]['scope_opener'] + 1 ); $i < $tokens[ $currScope ]['scope_closer']; $i++ ) {
			if ( T_FUNCTION !== $tokens[ $i ]['code'] ) {
				continue;
			}

			$next                  = $phpcsFile->findNext( T_STRING, $i );
			$this->_functionList[] = trim( $tokens[ $next ]['content'] );
		}

	}//end loadFunctionNamesInScope()

	/**
	 * Do nothing outside the scope. Has to be implemented accordingly to parent abstract class.
	 *
	 * @param File $phpcsFile PHPCS File.
	 * @param int  $stackPtr  Stack position.
	 */
	public function processTokenOutsideScope( File $phpcsFile, $stackPtr ) {}
}

<?php

/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Functions;

use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * This sniff enforces that certain functions are not
 * dynamically called.
 *
 * An example:
 *
 * <code>
 *   $func = 'func_num_args';
 *   $func();
 * </code>
 */
class DynamicCallsSniff implements \PHP_CodeSniffer_Sniff {
	/**
	 * Functions that should not be called dynamically.
	 *
	 * @var array
	 */

	private $_blacklisted_functions = array(
		'assert',
		'compact',
		'extract',
		'func_get_args',
		'func_get_arg',
		'func_num_args',
		'get_defined_vars',
		'mb_parse_str',
		'parse_str',
	);

	/**
	 * Array of functions encountered, along with their values.
	 * Populated on run-time.
	 *
	 * @var array
	 */

	private	$_variables_arr = array();


	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * We want everything variable- and function-related.
	 *
	 * @return array(int)
	 */
	public function register() {
		return
			array( T_VARIABLE => T_VARIABLE ) +
			Tokens::$functionNameTokens;

	}//end register()

	/**
	 * Processes the tokens that this sniff is interested in.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file where the token was found.
	 * @param int                         $stackPtr  The position in the stack where
	 *                                               the token was found.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$this->_tokens    = $phpcsFile->getTokens();
		$this->_phpcsFile = $phpcsFile;
		$this->_stackPtr = $stackPtr;

		$this->_collect_variables();
		
		$this->_find_dynamic_calls();

	}//end process()

	/**
	 * Finds any variable-definitions in the file being processed,
	 * and stores them internally in a private array. The data stored
	 * is the name of the variable and its assigned value.
	 *
	 * @return void
	 */

	function _collect_variables() {
		/*
		 * Find variable within the next semicolon 
		 */

		$t_item_key = $this->_phpcsFile->findNext(
			array( T_VARIABLE ),
			$this->_stackPtr,
			null,
			false,
			null,
			true
		);

		if ( false === $t_item_key ) {
			return;
		}

		$current_var_name = $this->_tokens[ $t_item_key ]["content"];


		/*
		 * Find encapsed string ( "" )
		 */
		$t_item_key = $this->_phpcsFile->findNext(
			array( T_CONSTANT_ENCAPSED_STRING ),
			$this->_stackPtr,
			null,
			false,
			null,
			true
		);

		if ( false === $t_item_key ) {
			return;
		}

		$current_var_value =
			$this->_tokens[ $t_item_key ]["content"];


		/*
		 * Find assignments ( $foo = "bar"; )
		 */

		$t_item_key = $this->_phpcsFile->findNext(
			array( T_EQUAL ),
			$this->_stackPtr,
			null,
			false,
			null,
			true
		);

		if ( false === $t_item_key ) {
			return;
		}

		if ( 1 !== $this->_tokens[ $t_item_key ]["length"] ) {
			return;
		}

		$this->_variables_arr[ $current_var_name ] =
			str_replace( "'", "", $current_var_value );

	} // end _collect_variables


	/**
	 * Find any dynamic calls being made using variables.
	 * Report on this when found, using name of the function
	 * in the message.
	 *
	 * @return void
	 */

	function _find_dynamic_calls() {
		/* 
		 * No variables detected; no basis for doing 
		 * anything 
		 */

		if ( empty( $this->_variables_arr ) ) {
			return;
		}

		/*
		 * Make sure we do have a variable
		 * in the stack, and within the next semicolon.
		 */

		$t_item_key = $this->_phpcsFile->findNext(
			array( T_VARIABLE ),
			$this->_stackPtr,
			null,
			false,
			null,
			true
		);

		if ( false === $t_item_key ) {
			return;
		}


		/*
		 * Check if we have an '(' somewhere next in the stack,
		 * but not outside of the next semicolon ( ';' ).
		 */

		$t_item_key  = $this->_phpcsFile->findNext(
			array( T_OPEN_PARENTHESIS ),
			$this->_stackPtr + 1,
			null,
			false,
			null,
			true
		);

		if ( false === $t_item_key ) {
			// None found, return
			return;
		}

		$t_item_val = $this->_tokens[ $t_item_key ];


		/* 
		 * If variable is not found in our registry of 
		 * variables, do not do anything, as we cannot be
		 * sure that the function being called is one of the
		 * blacklisted ones.
		 */

		if ( ! isset(
			$this->_variables_arr[
				$this->_tokens[ $this->_stackPtr ]["content"]
			]
		) ) {
			return;
		}

		/*
		 * We have a variable match, but make sure it contains name
		 * of a function which is on our blacklist.
		 */

		if ( ! in_array(
			$this->_variables_arr[
				$this->_tokens[ $this->_stackPtr ]["content"]
			],
			$this->_blacklisted_functions
		) ) {
			return;
		}


		// We do, so report.
		$this->_phpcsFile->addError(
			sprintf(
				"Dynamic calling is not recommended in the case of %s",
				$this->_variables_arr[
					$this->_tokens[
						$this->_stackPtr
					]["content"]
				]
			),
			$t_item_key,
			'DynamicCalls'
		);

	} // end _find_dynamic_calls
}

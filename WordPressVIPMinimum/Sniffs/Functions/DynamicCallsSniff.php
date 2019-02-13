<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Functions;

use WordPressVIPMinimum\Sniffs\Sniff;

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
 *
 * See here: http://php.net/manual/en/migration71.incompatible.php
 *
 * Note that this sniff does not catch all possible forms of dynamic
 * calling, only some.
 */
class DynamicCallsSniff extends Sniff {
	/**
	 * Functions that should not be called dynamically.
	 *
	 * @var array
	 */
	private $_blacklisted_functions = [
		'assert',
		'compact',
		'extract',
		'func_get_args',
		'func_get_arg',
		'func_num_args',
		'get_defined_vars',
		'mb_parse_str',
		'parse_str',
	];

	/**
	 * Array of functions encountered, along with their values.
	 * Populated on run-time.
	 *
	 * @var array
	 */
	private $_variables_arr = [];

	/**
	 * The position in the stack where the token was found.
	 *
	 * @var int
	 */
	private $_stackPtr;

	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * We want everything variable- and function-related.
	 *
	 * @return array(int)
	 */
	public function register() {
		return [ T_VARIABLE => T_VARIABLE ];
	}

	/**
	 * Processes the tokens that this sniff is interested in.
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {
		$this->_stackPtr = $stackPtr;

		// First collect all variables encountered and their values.
		$this->collect_variables();

		// Then find all dynamic calls, and report them.
		$this->find_dynamic_calls();
	}

	/**
	 * Finds any variable-definitions in the file being processed,
	 * and stores them internally in a private array. The data stored
	 * is the name of the variable and its assigned value.
	 *
	 * @return void
	 */
	private function collect_variables() {
		/*
		 * Make sure we are working with a variable,
		 * get its value if so.
		 */

		if (
			'T_VARIABLE' !==
				$this->tokens[ $this->_stackPtr ]['type']
		) {
			return;
		}

		$current_var_name = $this->tokens[
			$this->_stackPtr
		]['content'];

		/*
		 * Find assignments ( $foo = "bar"; )
		 * -- do this by finding all non-whitespaces, and
		 * check if the first one is T_EQUAL.
		 */

		$t_item_key = $this->phpcsFile->findNext(
			[ T_WHITESPACE ],
			$this->_stackPtr + 1,
			null,
			true,
			null,
			true
		);

		if ( false === $t_item_key ) {
			return;
		}

		if ( 'T_EQUAL' !== $this->tokens[ $t_item_key ]['type'] ) {
			return;
		}

		if ( 1 !== $this->tokens[ $t_item_key ]['length'] ) {
			return;
		}

		/*
		 * Find encapsulated string ( "" )
		 */
		$t_item_key = $this->phpcsFile->findNext(
			[ T_CONSTANT_ENCAPSED_STRING ],
			$t_item_key + 1,
			null,
			false,
			null,
			true
		);

		if ( false === $t_item_key ) {
			return;
		}

		/*
		 * We have found variable-assignment,
		 * register its name and value in the
		 * internal array for later usage.
		 */

		$current_var_value =
			$this->tokens[ $t_item_key ]['content'];

		$this->_variables_arr[ $current_var_name ] =
			str_replace( "'", '', $current_var_value );
	}

	/**
	 * Find any dynamic calls being made using variables.
	 * Report on this when found, using name of the function
	 * in the message.
	 *
	 * @return void
	 */
	private function find_dynamic_calls() {
		/*
		 * No variables detected; no basis for doing
		 * anything
		 */

		if ( empty( $this->_variables_arr ) ) {
			return;
		}

		/*
		 * Make sure we do have a variable to work with.
		 */

		if (
			'T_VARIABLE' !==
				$this->tokens[ $this->_stackPtr ]['type']
		) {
			return;
		}

		/*
		 * If variable is not found in our registry of
		 * variables, do nothing, as we cannot be
		 * sure that the function being called is one of the
		 * blacklisted ones.
		 */

		if ( ! isset(
			$this->_variables_arr[
				$this->tokens[ $this->_stackPtr ]['content']
			]
		) ) {
			return;
		}

		/*
		 * Check if we have an '(' next, or separated by whitespaces
		 * from our current position.
		 */

		$i = 0;

		do {
			$i++;
		} while (
			'T_WHITESPACE' ===
				$this->tokens[
					$this->_stackPtr + $i
				]['type']
		);

		if (
			'T_OPEN_PARENTHESIS' !==
				$this->tokens[
					$this->_stackPtr + $i
				]['type']
		) {
			return;
		}

		$t_item_key = $this->_stackPtr + $i;

		/*
		 * We have a variable match, but make sure it contains name
		 * of a function which is on our blacklist.
		 */

		if ( ! in_array(
			$this->_variables_arr[
				$this->tokens[ $this->_stackPtr ]['content']
			],
			$this->_blacklisted_functions,
			true
		) ) {
			return;
		}

		// We do, so report.
		$message = 'Dynamic calling is not recommended in the case of %s.';
		$data    = [ $this->_variables_arr[ $this->tokens[ $this->_stackPtr ]['content'] ] ];
		$this->phpcsFile->addError( $message, $t_item_key, 'DynamicCalls', $data );
	}
}

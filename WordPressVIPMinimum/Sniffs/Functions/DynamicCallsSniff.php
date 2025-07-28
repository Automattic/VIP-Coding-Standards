<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Functions;

use PHP_CodeSniffer\Sniffs\DeprecatedSniff;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\TextStrings;
use WordPressVIPMinimum\Sniffs\Sniff;

/**
 * This sniff enforces that certain functions are not dynamically called.
 *
 * An example:
 * ```php
 *   $func = 'func_num_args';
 *   $func();
 * ```
 *
 * Note that this sniff does not catch all possible forms of dynamic calling, only some.
 *
 * @link http://php.net/manual/en/migration71.incompatible.php
 */
class DynamicCallsSniff extends Sniff implements DeprecatedSniff {

	/**
	 * Functions that should not be called dynamically.
	 *
	 * @var array<string, bool>
	 */
	private $disallowed_functions = [
		'assert'           => true,
		'compact'          => true,
		'extract'          => true,
		'func_get_args'    => true,
		'func_get_arg'     => true,
		'func_num_args'    => true,
		'get_defined_vars' => true,
		'mb_parse_str'     => true,
		'parse_str'        => true,
	];

	/**
	 * Potential end tokens for which the end pointer has to be set back by one.
	 *
	 * {@internal The PHPCS `findEndOfStatement()` method is not completely consistent
	 * in how it returns the statement end. This is just a simple way to bypass
	 * the inconsistency for our purposes.}
	 *
	 * @var array<int|string, true>
	 */
	private $inclusiveStopPoints = [
		T_COLON        => true,
		T_COMMA        => true,
		T_DOUBLE_ARROW => true,
		T_SEMICOLON    => true,
	];

	/**
	 * Array of variable assignments encountered, along with their values.
	 *
	 * Populated at run-time.
	 *
	 * @var array<string, string> The key is the name of the variable, the value, its assigned value.
	 */
	private $variables_arr = [];

	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * @return array<int|string>
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
		// First collect all variables encountered and their values.
		$this->collect_variables( $stackPtr );

		// Then find all dynamic calls, and report them.
		$this->find_dynamic_calls( $stackPtr );
	}

	/**
	 * Finds any variable-definitions in the file being processed and stores them
	 * internally in a private array.
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 *
	 * @return void
	 */
	private function collect_variables( $stackPtr ) {

		$current_var_name = $this->tokens[ $stackPtr ]['content'];

		/*
		 * Find assignments ( $foo = "bar"; ) by finding all non-whitespaces,
		 * and checking if the first one is T_EQUAL.
		 */
		$t_item_key = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true );
		if ( $t_item_key === false || $this->tokens[ $t_item_key ]['code'] !== T_EQUAL ) {
			return;
		}

		/*
		 * Find assignments which only assign a plain text string.
		 */
		$end_of_statement = $this->phpcsFile->findEndOfStatement( ( $t_item_key + 1 ) );
		if ( isset( $this->inclusiveStopPoints[ $this->tokens[ $end_of_statement ]['code'] ] ) === true ) {
			--$end_of_statement;
		}

		$value_ptr = null;
		for ( $i = $t_item_key + 1; $i <= $end_of_statement; $i++ ) {
			if ( isset( Tokens::$emptyTokens[ $this->tokens[ $i ]['code'] ] ) === true ) {
				continue;
			}

			if ( $this->tokens[ $i ]['code'] !== T_CONSTANT_ENCAPSED_STRING ) {
				// Not a plain text string value. Value cannot be determined reliably.
				return;
			}

			$value_ptr = $i;
		}

		if ( isset( $value_ptr ) === false ) {
			// Parse error. Bow out.
			return;
		}

		/*
		 * If we reached the end of the loop and the $value_ptr was set, we know for sure
		 * this was a plain text string variable assignment.
		 */
		$current_var_value = TextStrings::stripQuotes( $this->tokens[ $value_ptr ]['content'] );

		if ( isset( $this->disallowed_functions[ $current_var_value ] ) === false ) {
			// Text string is not one of the ones we're looking for.
			return;
		}

		/*
		 * Register the variable name and value in the internal array for later usage.
		 */
		$this->variables_arr[ $current_var_name ] = $current_var_value;
	}

	/**
	 * Find any dynamic calls being made using variables.
	 *
	 * Report on this when found, using the name of the function in the message.
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 *
	 * @return void
	 */
	private function find_dynamic_calls( $stackPtr ) {
		// No variables detected; no basis for doing anything.
		if ( empty( $this->variables_arr ) ) {
			return;
		}

		/*
		 * If variable is not found in our registry of variables, do nothing, as we cannot be
		 * sure that the function being called is one of the disallowed ones.
		 */
		if ( ! isset( $this->variables_arr[ $this->tokens[ $stackPtr ]['content'] ] ) ) {
			return;
		}

		/*
		 * Check if we have an '(' next.
		 */
		$next = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );
		if ( $next === false || $this->tokens[ $next ]['code'] !== T_OPEN_PARENTHESIS ) {
			return;
		}

		$message = 'Dynamic calling is not recommended in the case of %s().';
		$data    = [ $this->variables_arr[ $this->tokens[ $stackPtr ]['content'] ] ];
		$this->phpcsFile->addError( $message, $stackPtr, 'DynamicCalls', $data );
	}

	/**
	 * Provide the version number in which the sniff was deprecated.
	 *
	 * @return string
	 */
	public function getDeprecationVersion() {
		return 'VIP-Coding-Standard v3.1.0';
	}

	/**
	 * Provide the version number in which the sniff will be removed.
	 *
	 * @return string
	 */
	public function getRemovalVersion() {
		return 'VIP-Coding-Standard v4.0.0';
	}

	/**
	 * Provide a custom message to display with the deprecation.
	 *
	 * @return string
	 */
	public function getDeprecationMessage() {
		return '';
	}
}

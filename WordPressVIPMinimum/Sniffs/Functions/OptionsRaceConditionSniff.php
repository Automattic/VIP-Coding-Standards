<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Functions;

use WordPressVIPMinimum\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\AbstractScopeSniff;

/**
 * This sniff checks to see if the deletion of an option is immediately followed by an addition of the same option.
 */
class OptionsRaceConditionSniff extends AbstractScopeSniff {

	/**
	 * Function name to delete option.
	 *
	 * @var string
	 */
	private $delete_option = 'delete_option';

	/**
	 * Function name to add option.
	 *
	 * @var string
	 */
	private $add_option = 'add_option';

	/**
	 * Constructs the test with the tokens it wishes to listen for.
	 */
	public function __construct() {
		parent::__construct(
			[ T_FUNCTION ],
			[ T_STRING ]
		);
	}

	/**
	 * Processes the function tokens within the class.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file where this token was found.
	 * @param int                         $stackPtr  The position where the token was found.
	 * @param int                         $currScope The current scope opener token.
	 *
	 * @return void
	 */
	protected function processTokenWithinScope( File $phpcsFile, $stackPtr, $currScope ) {
		$tokens = $phpcsFile->getTokens();

		if ( $tokens[ $stackPtr ]['content'] !== $this->delete_option ) {
			$stackPtr = $phpcsFile->findNext(
				[ T_STRING ],
				$stackPtr + 1,
				null,
				true,
				$this->delete_option
			);

			if ( ! $stackPtr ) {
				return; // No delete_option found, bail.
			}
		}

		$delete_option_scope_start = $phpcsFile->findNext(
			Tokens::$emptyTokens,
			$stackPtr + 1,
			null,
			true
		);

		if ( ! $delete_option_scope_start || $tokens[ $delete_option_scope_start ]['code'] !== T_OPEN_PARENTHESIS ) {
			return; // Not a function call, bail.
		}

		$delete_option_semicolon = $phpcsFile->findNext(
			[ T_SEMICOLON ],
			$tokens[ $delete_option_scope_start ]['parenthesis_closer'] + 1,
			null,
			false
		);

		$delete_option_key = $phpcsFile->findNext(
			Tokens::$emptyTokens,
			$delete_option_scope_start + 1,
			null,
			true
		);

		$add_option = $phpcsFile->findNext(
			Tokens::$emptyTokens,
			$delete_option_semicolon + 1,
			null,
			true
		);

		$message = 'Concurrent calls to `delete_option()` and `add_option()` for %s may lead to race conditions in persistent object caching. Please consider using `update_option()` in place of both function calls, as it will also add the option if it does not exist.';

		if ( $tokens[ $add_option ]['code'] === T_IF ) {
			// Check inside scope of first IF statement after for `add_option()` being called.
			$add_option_inside_if = $phpcsFile->findNext(
				[ T_STRING ],
				$tokens[ $add_option ]['scope_opener'] + 1,
				null,
				false,
				$this->add_option
			);

			if ( ! $add_option_inside_if || $add_option_inside_if > $tokens[ $add_option ]['scope_closer'] ) {
				return; // No add_option() call inside first IF statement or add_option found not in IF scope.
			}

			$add_option_inside_if_scope_start = $phpcsFile->findNext(
				Tokens::$emptyTokens,
				$add_option_inside_if + 1,
				null,
				true
			);

			if ( ! $add_option_inside_if_scope_start || $tokens[ $add_option_inside_if_scope_start ]['code'] !== T_OPEN_PARENTHESIS ) {
				return; // Not a function call, bail.
			}

			$add_option_inside_if_option_key = $phpcsFile->findNext(
				Tokens::$emptyTokens,
				$add_option_inside_if_scope_start + 1,
				null,
				true
			);

			if ( $add_option_inside_if_option_key && $this->is_same_option_key( $tokens, $add_option_inside_if_option_key, $delete_option_key ) ) {
				$phpcsFile->addWarning( $message, $add_option_inside_if_option_key, 'OptionsRaceCondition' );
			}

			// Walk ahead out of IF control structure.
			$add_option = $phpcsFile->findNext(
				Tokens::$emptyTokens,
				$tokens[ $add_option ]['scope_closer'] + 1,
				null,
				true
			);
		}

		if ( $tokens[ $add_option ]['code'] === T_VARIABLE ) {
			// Account for variable assignments.
			$equals = $phpcsFile->findNext(
				Tokens::$emptyTokens,
				$add_option + 1,
				null,
				true
			);

			if ( $tokens[ $equals ]['code'] === T_EQUAL ) {
				$add_option = $phpcsFile->findNext(
					Tokens::$emptyTokens,
					$equals + 1,
					null,
					true
				);
			}
		}

		if ( $tokens[ $add_option ]['code'] !== T_STRING || $tokens[ $add_option ]['content'] !== $this->add_option ) {
			return; // add_option() isn't immediately following delete_option(), bail.
		}

		$add_option_scope_start = $phpcsFile->findNext(
			Tokens::$emptyTokens,
			$add_option + 1,
			null,
			true
		);

		if ( ! $add_option_scope_start || $tokens[ $add_option_scope_start ]['code'] !== T_OPEN_PARENTHESIS ) {
			return; // Not a function call, bail.
		}

		// Check if it's the same option being deleted earlier.
		$add_option_key = $phpcsFile->findNext(
			Tokens::$emptyTokens,
			$add_option_scope_start + 1,
			null,
			true
		);

		if ( $this->is_same_option_key( $tokens, $add_option_key, $delete_option_key ) ) {
			$phpcsFile->addWarning( $message, $add_option_key, 'OptionsRaceCondition' );
			return;
		}
	}

	/**
	 * Processes a token that is found within the scope that this test is
	 * listening to.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file where this token was found.
	 * @param int                         $stackPtr  The position in the stack where this
	 *                                               token was found.
	 * @return void
	 */
	protected function processTokenOutsideScope( File $phpcsFile, $stackPtr ) {
	}

	/**
	 * Check if option is the same.
	 *
	 * @param array $tokens        List of PHPCS tokens.
	 * @param int   $first_option  Stack position of first option.
	 * @param int   $second_option Stack position of second option to match against.
	 *
	 * @return false
	 */
	private function is_same_option_key( $tokens, $first_option, $second_option ) {
		return $tokens[ $first_option ]['code'] === $tokens[ $second_option ]['code'] &&
		$tokens[ $first_option ]['content'] === $tokens[ $second_option ]['content'];
	}
}

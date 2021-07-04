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
			// delete_option is not first function found.
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

		$delete_option_name = $phpcsFile->findNext(
			Tokens::$emptyTokens,
			$delete_option_scope_start + 1,
			null,
			true
		);

		$delete_option_concat = $phpcsFile->findNext(
			Tokens::$emptyTokens,
			$delete_option_name + 1,
			null,
			true
		);

		$delete_option_name = $this->trim_strip_quotes( $tokens[ $delete_option_name ]['content'] );

		$is_delete_option_concat = $tokens[ $delete_option_concat ]['code'] === T_STRING_CONCAT;
		if ( $is_delete_option_concat ) {
			// If option name is concatenated, we need to build it out.
			$delete_option_concat = $phpcsFile->findNext(
				Tokens::$emptyTokens,
				$delete_option_concat + 1,
				null,
				true
			);

			while ( $delete_option_concat < $tokens[ $delete_option_scope_start ]['parenthesis_closer'] ) {
				$delete_option_name .= $this->trim_strip_quotes( $tokens[ $delete_option_concat ]['content'] );

				$delete_option_concat = $phpcsFile->findNext(
					array_merge( Tokens::$emptyTokens, [ T_STRING_CONCAT ] ),
					$delete_option_concat + 1,
					null,
					true
				);
			}
		}

		$delete_option_scope_end = $phpcsFile->findNext(
			[ T_SEMICOLON ],
			$tokens[ $delete_option_scope_start ]['parenthesis_closer'] + 1,
			null,
			false
		);

		if ( ! $delete_option_scope_end ) {
			return; // Something went wrong with the syntax.
		}

		$add_option = $phpcsFile->findNext(
			Tokens::$emptyTokens,
			$delete_option_scope_end + 1,
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

			$add_option_inside_if_option_name = $phpcsFile->findNext(
				Tokens::$emptyTokens,
				$add_option_inside_if_scope_start + 1,
				null,
				true
			);

			$add_option_inside_if_concat = $phpcsFile->findNext(
				Tokens::$emptyTokens,
				$add_option_inside_if_option_name + 1,
				null,
				true
			);

			$add_option_inside_if_option_name = $this->trim_strip_quotes( $tokens[ $add_option_inside_if_option_name ]['content'] );

			if ( $is_delete_option_concat && $tokens[ $add_option_inside_if_concat ]['code'] === T_STRING_CONCAT ) {
				$add_option_inside_if_concat = $phpcsFile->findNext(
					array_merge( Tokens::$emptyTokens, [ T_STRING_CONCAT ] ),
					$add_option_inside_if_concat + 1,
					null,
					true
				);

				$add_option_inside_if_scope_end = $phpcsFile->findNext(
					[ T_COMMA ],
					$add_option_inside_if_concat + 1,
					null,
					false
				);

				if ( ! $add_option_inside_if_scope_end ) {
					return; // Something went wrong.
				}

				while ( $add_option_inside_if_concat < $add_option_inside_if_scope_end ) {
					$add_option_inside_if_option_name .= $this->trim_strip_quotes( $tokens[ $add_option_inside_if_concat ]['content'] );

					$add_option_inside_if_concat = $phpcsFile->findNext(
						array_merge( Tokens::$emptyTokens, [ T_STRING_CONCAT ] ),
						$add_option_inside_if_concat + 1,
						null,
						true
					);
				}
			}

			if ( $this->is_same_option_key( $delete_option_name, $add_option_inside_if_option_name ) ) {
				$phpcsFile->addWarning( $message, $add_option_inside_if_scope_start, 'OptionsRaceCondition' );
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

		$add_option_name = $phpcsFile->findNext(
			Tokens::$emptyTokens,
			$add_option_scope_start + 1,
			null,
			true
		);

		$add_option_concat = $phpcsFile->findNext(
			Tokens::$emptyTokens,
			$add_option_name + 1,
			null,
			true
		);

		$add_option_name = $this->trim_strip_quotes( $tokens[ $add_option_name ]['content'] );
		if ( $is_delete_option_concat && $tokens[ $add_option_concat ]['code'] === T_STRING_CONCAT ) {
			$add_option_concat = $phpcsFile->findNext(
				Tokens::$emptyTokens,
				$add_option_concat + 1,
				null,
				true
			);

			$add_option_scope_end = $phpcsFile->findNext(
				[ T_COMMA ],
				$add_option_concat + 1,
				null,
				false
			);

			if ( ! $add_option_scope_end ) {
				return; // Something went wrong.
			}

			while ( $add_option_concat < $add_option_scope_end ) {
				$add_option_name .= $this->trim_strip_quotes( $tokens[ $add_option_concat ]['content'] );

				$add_option_concat = $phpcsFile->findNext(
					array_merge( Tokens::$emptyTokens, [ T_STRING_CONCAT ] ),
					$add_option_concat + 1,
					null,
					true
				);
			}
		}

		if ( $this->is_same_option_key( $delete_option_name, $add_option_name ) ) {
			$phpcsFile->addWarning( $message, $add_option_scope_start, 'OptionsRaceCondition' );
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
	 * @param string $option_name             Option name.
	 * @param string $option_name_to_compare  Option name to compare against.
	 *
	 * @return false
	 */
	private function is_same_option_key( $option_name, $option_name_to_compare ) {
		return $option_name === $option_name_to_compare;
	}

	/**
	 * Trim whitespace and strip quotes surrounding an arbitrary string.
	 *
	 * @param string $string The raw string.
	 * @return string String without whitespace or quotes around it.
	 */
	public function trim_strip_quotes( $string ) {
		return trim( preg_replace( '`^([\'"])(.*)\1$`Ds', '$2', $string ) );
	}
}

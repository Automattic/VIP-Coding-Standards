<?php

namespace WordPressVIPMinimum\Sniffs\Functions;

use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * This sniff will check for `strip_slashes()` usage and return a relavant error
 */
class StripTagsNotAllowedSniff implements \PHP_CodeSniffer_Sniff {

	/**
	 * Tokens of the whole file.
	 *
	 * @var array
	 */
	private $_tokens = array();

	/**
	 * The phpcs file.
	 *
	 * @var File
	 */
	private $_phpcsFile;


	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * @return array(int)
	 */
	public function register() {

		return Tokens::$functionNameTokens;

	}


	/**
	 * Processes the tokens that this sniff is interested in.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file where the token was found.
	 * @param int                         $stackPtr  The position in the stack where
	 *                                               the token was found.
	 *
	 * @return bool
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$this->_tokens    = $phpcsFile->getTokens();
		$this->_phpcsFile = $phpcsFile;

		if ( 'strip_tags' !== $this->_tokens[ $stackPtr ]['content'] ) {
			// We only care for strip_slashes function.
			return false;
		}

		if ( ! $this->isFunctionCall( $stackPtr ) ) {
			// Not a function call.
			return false;
		}

		if ( $phpcsFile->findNext( T_COMMA, $stackPtr + 1, null, false, null, true ) ) {
			// If there is a comma inside - there are more than 1 argument to strip_tags().
			$phpcsFile->addError( '`strip_tags()` does not strip CSS and JS in between the script and style tags. Use `wp_kses()` instead to allow only the HTML you need.', $stackPtr, 'stripTagsNotAllowed' );
		} else {
			// `strip_tags()` called with 1 or less arguments.
			$phpcsFile->addError( '`strip_tags()` does not strip CSS and JS in between the script and style tags. Use `wp_strip_all_tags()` to strip all tags.', $stackPtr, 'stripTagsNotAllowed' );
		}
	}


	/**
	 * Check whether the currently examined code is a function call.
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return bool
	 */
	private function isFunctionCall( $stackPtr ) {

		$tokens    = $this->_tokens;
		$phpcsFile = $this->_phpcsFile;

		if ( false === in_array( $tokens[ $stackPtr ]['code'], Tokens::$functionNameTokens, true ) ) {
			return false;
		}

		// Find the next non-empty token.
		$openBracket = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );

		if ( T_OPEN_PARENTHESIS !== $tokens[ $openBracket ]['code'] ) {
			// Not a function call.
			return false;
		}

		// Find the previous non-empty token.
		$search   = Tokens::$emptyTokens;
		$search[] = T_BITWISE_AND;
		$previous = $phpcsFile->findPrevious( $search, ( $stackPtr - 1 ), null, true );
		if ( T_FUNCTION === $tokens[ $previous ]['code'] ) {
			// It's a function definition, not a function call.
			return false;
		}

		return true;
	}

}

<?php
/**
 * WordPress-VIP-Minimum Coding Standard.
 *
 *  @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\VIP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Flag suspicious WP_Query and get_posts params.
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class EscapingVoidReturnFunctionsSniff implements Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(
			T_STRING,
		);
	}

	/**
	 * Process this test when one of its tokens is encountered
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		if ( 0 !== strpos( $tokens[ $stackPtr ]['content'], 'esc_' ) && 0 !== strpos( $tokens[ $stackPtr ]['content'], 'wp_kses' ) ) {
			// Not what we are looking for.
			return;
		}

		$next_token = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );

		if ( T_OPEN_PARENTHESIS !== $tokens[ $next_token ]['code'] ) {
			// Not a function call.
			return;
		}

		$next_token = $phpcsFile->findNext( Tokens::$emptyTokens, ( $next_token + 1 ), null, true );

		if ( T_STRING !== $tokens[ $next_token ]['code'] ) {
			// Not what we are looking for.
			return;
		}

		if ( 0 === strpos( $tokens[ $next_token ]['content'], '_e' ) ) {
			$phpcsFile->addError( sprintf( 'Attempting to escape `%s()` which is printing its output.', $tokens[ $next_token ]['content'] ), $stackPtr, 'escapingVoidReturningFunction' );
			return;
		}
	}

}

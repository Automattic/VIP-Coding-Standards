<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\Security;

use WordPressVIPMinimum\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Flag suspicious WP_Query and get_posts params.
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class EscapingVoidReturnFunctionsSniff extends Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [
			T_STRING,
		];
	}

	/**
	 * Process this test when one of its tokens is encountered
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		if ( 0 !== strpos( $this->tokens[ $stackPtr ]['content'], 'esc_' ) && 0 !== strpos( $this->tokens[ $stackPtr ]['content'], 'wp_kses' ) ) {
			// Not what we are looking for.
			return;
		}

		$next_token = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true );

		if ( T_OPEN_PARENTHESIS !== $this->tokens[ $next_token ]['code'] ) {
			// Not a function call.
			return;
		}

		$next_token = $this->phpcsFile->findNext( Tokens::$emptyTokens, $next_token + 1, null, true );

		if ( T_STRING !== $this->tokens[ $next_token ]['code'] ) {
			// Not what we are looking for.
			return;
		}

		if ( 0 === strpos( $this->tokens[ $next_token ]['content'], '_e' ) ) {
			$message = 'Attempting to escape `%s()` which is printing its output.';
			$data    = [ $this->tokens[ $next_token ]['content'] ];
			$this->phpcsFile->addError( $message, $stackPtr, 'Found', $data );
			return;
		}
	}

}

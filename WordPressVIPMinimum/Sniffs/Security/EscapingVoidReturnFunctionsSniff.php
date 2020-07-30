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
 * Flag functions that don't return anything, yet are wrapped in an escaping function call.
 *
 * E.g. esc_html( _e( 'foo' ) );
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

		if ( strpos( $this->tokens[ $stackPtr ]['content'], 'esc_' ) !== 0 && strpos( $this->tokens[ $stackPtr ]['content'], 'wp_kses' ) !== 0 ) {
			// Not what we are looking for.
			return;
		}

		$next_token = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true );

		if ( $this->tokens[ $next_token ]['code'] !== T_OPEN_PARENTHESIS ) {
			// Not a function call.
			return;
		}

		$next_token = $this->phpcsFile->findNext( Tokens::$emptyTokens, $next_token + 1, null, true );

		if ( $this->tokens[ $next_token ]['code'] !== T_STRING ) {
			// Not what we are looking for.
			return;
		}

		if ( isset( $this->printingFunctions[ $this->tokens[ $next_token ]['content'] ] ) ) {
			$message = 'Attempting to escape `%s()` which is printing its output.';
			$data    = [ $this->tokens[ $next_token ]['content'] ];
			$this->phpcsFile->addError( $message, $stackPtr, 'Found', $data );
			return;
		}
	}

}

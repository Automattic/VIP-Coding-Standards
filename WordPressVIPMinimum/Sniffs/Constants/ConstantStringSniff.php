<?php
/**
 * WordPress-VIP-Minimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\Constants;

use WordPressVIPMinimum\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Sniff for properly using constant name when checking whether a constant is defined.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class ConstantStringSniff extends Sniff {

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
	 * Process this test when one of its tokens is encountered.
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		if ( false === in_array( $this->tokens[ $stackPtr ]['content'], [ 'define', 'defined' ], true ) ) {
			return;
		}

		// Find the next non-empty token.
		$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true );

		if ( T_OPEN_PARENTHESIS !== $this->tokens[ $nextToken ]['code'] ) {
			// Not a function call.
			return;
		}

		if ( false === isset( $this->tokens[ $nextToken ]['parenthesis_closer'] ) ) {
			// Not a function call.
			return;
		}

		$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $nextToken + 1, null, true, null, true );

		if ( T_CONSTANT_ENCAPSED_STRING !== $this->tokens[ $nextToken ]['code'] ) {
			$message = 'Constant name, as a string, should be used along with `%s()`.';
			$data    = [ $this->tokens[ $stackPtr ]['content'] ];
			$this->phpcsFile->addError( $message, $nextToken, 'NotCheckingConstantName', $data );
			return;
		}
	}

}

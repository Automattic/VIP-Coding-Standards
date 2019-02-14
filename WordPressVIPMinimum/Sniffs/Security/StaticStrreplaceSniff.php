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
 * Restricts usage of str_replace with all 3 params being static.
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class StaticStrreplaceSniff extends Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return Tokens::$functionNameTokens;
	}

	/**
	 * Process this test when one of its tokens is encountered
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		if ( 'str_replace' !== $this->tokens[ $stackPtr ]['content'] ) {
			return;
		}

		$openBracket = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true );

		if ( T_OPEN_PARENTHESIS !== $this->tokens[ $openBracket ]['code'] ) {
			return;
		}

		$next_start_ptr = $openBracket + 1;
		for ( $i = 0; $i < 3; $i++ ) {
			$param_ptr = $this->phpcsFile->findNext( array_merge( Tokens::$emptyTokens, [ T_COMMA ] ), $next_start_ptr, null, true );

			if ( T_ARRAY === $this->tokens[ $param_ptr ]['code'] ) {
				$openBracket = $this->phpcsFile->findNext( Tokens::$emptyTokens, $param_ptr + 1, null, true );
				if ( T_OPEN_PARENTHESIS !== $this->tokens[ $openBracket ]['code'] ) {
					return;
				}

				// Find the closing bracket.
				$closeBracket = $this->tokens[ $openBracket ]['parenthesis_closer'];

				$array_item_ptr = $this->phpcsFile->findNext( array_merge( Tokens::$emptyTokens, [ T_COMMA ] ), $openBracket + 1, $closeBracket, true );
				while ( false !== $array_item_ptr ) {

					if ( T_CONSTANT_ENCAPSED_STRING !== $this->tokens[ $array_item_ptr ]['code'] ) {
						return;
					}
					$array_item_ptr = $this->phpcsFile->findNext( array_merge( Tokens::$emptyTokens, [ T_COMMA ] ), $array_item_ptr + 1, $closeBracket, true );
				}

				$next_start_ptr = $closeBracket + 1;
				continue;

			}

			if ( T_CONSTANT_ENCAPSED_STRING !== $this->tokens[ $param_ptr ]['code'] ) {
				return;
			}

			$next_start_ptr = $param_ptr + 1;

		}

		$message = 'This code pattern is often used to run a very dangerous shell programs on your server. The code in these files needs to be reviewed, and possibly cleaned.';
		$this->phpcsFile->addError( $message, $stackPtr, 'StaticStrreplace' );
	}
}

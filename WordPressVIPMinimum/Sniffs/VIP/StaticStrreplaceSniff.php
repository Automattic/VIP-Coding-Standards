<?php
/**
 * WordPress-VIP-Minimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\VIP;

use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * Restricts usage of str_replace with all 3 params being static.
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class StaticStrreplaceSniff implements \PHP_CodeSniffer_Sniff {

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
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		if ( 'str_replace' !== $tokens[ $stackPtr ]['content'] ) {
			return;
		}

		$openBracket = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );

		if ( T_OPEN_PARENTHESIS !== $tokens[ $openBracket ]['code'] ) {
			return;
		}

		$next_start_ptr = $openBracket + 1;
		for ( $i = 0; $i < 3; $i++ ) {
			$param_ptr = $phpcsFile->findNext( array_merge( Tokens::$emptyTokens, array( T_COMMA ) ), $next_start_ptr, null, true );

			if ( T_ARRAY === $tokens[ $param_ptr ]['code'] ) {
				$openBracket = $phpcsFile->findNext( Tokens::$emptyTokens, ( $param_ptr + 1 ), null, true );
				if ( T_OPEN_PARENTHESIS !== $tokens[ $openBracket ]['code'] ) {
					return;
				}

				// Find the closing bracket.
				$closeBracket = $tokens[ $openBracket ]['parenthesis_closer'];

				$array_item_ptr = $phpcsFile->findNext( array_merge( Tokens::$emptyTokens, array( T_COMMA ) ), ( $openBracket + 1 ), $closeBracket, true );
				while ( false !== $array_item_ptr ) {

					if ( T_CONSTANT_ENCAPSED_STRING !== $tokens[ $array_item_ptr ]['code'] ) {
						return;
					}
					$array_item_ptr = $phpcsFile->findNext( array_merge( Tokens::$emptyTokens, array( T_COMMA ) ), ( $array_item_ptr + 1 ), $closeBracket, true );
				}

				$next_start_ptr = $closeBracket + 1;
				continue;

			} elseif ( T_CONSTANT_ENCAPSED_STRING !== $tokens[ $param_ptr ]['code'] ) {
				return;
			}

			$next_start_ptr = $param_ptr + 1;

		}

		$phpcsFile->addError( sprintf( 'This code pattern is often used to run a very dangerous shell programs on your server. The code in these files needs to be reviewed, and possibly cleaned.', $tokens[ $stackPtr ]['content'] ), $stackPtr, 'StaticStrreplace' );
	}//end process()
}

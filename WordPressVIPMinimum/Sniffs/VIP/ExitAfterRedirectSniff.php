<?php
/**
 * WordPress-VIP-Minimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\VIP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Require `exit;` being called after wp_redirect and wp_safe_redirect.
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class ExitAfterRedirectSniff implements Sniff {

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

		if ( 'wp_redirect' !== $tokens[ $stackPtr ]['content'] && 'wp_safe_redirect' !== $tokens[ $stackPtr ]['content'] ) {
			return;
		}

		$openBracket = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );

		if ( T_OPEN_PARENTHESIS !== $tokens[ $openBracket ]['code'] ) {
			return;
		}

		$next_token = $phpcsFile->findNext( array_merge( Tokens::$emptyTokens, array( T_SEMICOLON, T_CLOSE_PARENTHESIS ) ), ( $tokens[ $openBracket ]['parenthesis_closer'] + 1 ), null, true );

		if ( T_OPEN_CURLY_BRACKET === $tokens[ $next_token ]['code'] ) {
			$is_exit_in_scope = false;
			for ( $i = $tokens[ $next_token ]['scope_opener']; $i <= $tokens[ $next_token ]['scope_closer']; $i++ ) {
				if ( T_EXIT === $tokens[ $i ]['code'] ) {
					$is_exit_in_scope = true;
				}
			}
			if ( false === $is_exit_in_scope ) {
				$phpcsFile->addError( sprintf( '`%s()` should almost always be followed by a call to `exit;`', $tokens[ $stackPtr ]['content'] ), $stackPtr, 'NoExitInConditional' );
			}
		} elseif ( T_EXIT !== $tokens[ $next_token ]['code'] ) {
			$phpcsFile->addError( sprintf( '`%s()` should almost always be followed by a call to `exit;`', $tokens[ $stackPtr ]['content'] ), $stackPtr, 'NoExit' );
		}
	}//end process()
}

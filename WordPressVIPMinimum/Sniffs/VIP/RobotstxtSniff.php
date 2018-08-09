<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package Automattic/VIP-Coding-Standards
 * @link    https://github.com/Automattic/VIP-Coding-Standards
 * @license https://github.com/Automattic/VIP-Coding-Standards/blob/master/LICENSE.md GPL v2 or later.
 */

/**
 * This sniff searches for `do_robotstxt` action hooked callback and thows an internal reminder
 * for VIP devs to flush related caches in order to make the change actually happen in production
 */
class WordPressVIPminimum_Sniffs_VIP_RobotstxtSniff implements PHP_CodeSniffer_Sniff {
	
	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * @return array(int)
	 */
	public function register() {
		return PHP_CodeSniffer_Tokens::$functionNameTokens;
	}

	/**
	 * Processes the tokens that this sniff is interested in.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file where the token 
	 *					  was found.
	 * @param int                  $stackPtr  The position in the stack
	 *					  where the token was found.
	 *
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		
		$tokens = $phpcsFile->getTokens();
	
		$functionName = $tokens[$stackPtr]['content'];

		if ( 'add_action' !== $functionName ) {
			return;
		}

		$actionNamePtr = $phpcsFile->findNext(
			array_merge( PHP_CodeSniffer_Tokens::$emptyTokens, array( T_OPEN_PARENTHESIS ) ), //types
			$stackPtr + 1, //start
			null, //end
			true, //exclude
			null, //value,
			true //local
		);

		if ( ! $actionNamePtr ) {
			// Something is wrong
			return;
		}

		if ( 'do_robotstxt' === substr( $tokens[$actionNamePtr]['content'], 1, -1 ) ) {
			$phpcsFile->addWarning( 'Internal note: remember to flush robots.txt nginx cache after deploying this revision', $stackPtr );
		}

	}
}

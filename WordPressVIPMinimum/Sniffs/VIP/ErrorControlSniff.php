<?php
/**
 * WordPress-VIP-Minimum Coding Standard.
 *
 *  @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\VIP;

use PHP_CodeSniffer_File as File;

/**
 * Restricts usage of error control operators. Currently only the at sign.
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class ErrorControlSniff implements \PHP_CodeSniffer_Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(
			T_ASPERAND,
		);
	}

	/**
	 * Process this test when one of its tokens is encoutered
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		$phpcsFile->addError( sprintf( 'The code shouldn\'t use error control operators (%s). The call should be wrapped in appropriate checks.', $tokens[ $stackPtr ]['content'] ), $stackPtr, 'WordPressVIPMinimum.VIP.ErrorControl' );
	}

}

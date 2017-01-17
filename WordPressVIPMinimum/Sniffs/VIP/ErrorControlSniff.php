<?php
/**
 * WordPress-VIP-Minimum Coding Standard.
 *
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

/**
 * Restricts usage of error control operators. Currently only the at sign
 */
class WordPressVIPMinimum_Sniffs_VIP_ErrorControlSniff implements PHP_CodeSniffer_Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(
			T_ASPERAND
		);
	}

	/**
	 * Process this test when one of its tokens is encoutnered
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int				   $stackPtr  The position of the current token in the stack passed in $tokens
	 *
	 * $return void
	 */ 
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		$phpcsFile->addError( sprintf( "The code shouldn't use error control operators (%s). The call should be wrapped in appropriate checks.", $tokens[$stackPtr]['content'] ), $stackPtr );
	}

}

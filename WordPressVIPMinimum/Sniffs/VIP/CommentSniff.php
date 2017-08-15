<?php
/**
 * WordPress-VIP-Minimum Coding Standard.
 *
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

/**
 * Restricts usage of error control operators. Currently only the at sign
 */
class WordPressVIPMinimum_Sniffs_VIP_CommentSniff implements PHP_CodeSniffer_Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(
			T_DOC_COMMENT,
			T_COMMENT,
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

		if ( false !== strpos( $tokens[$stackPtr]['content'], 'VIP' ) ) {
			$phpcsFile->addWarning( sprintf( "The code contains VIP comment. Make sure the diff is not removing it w/o properly addressing any hotfixes.", $tokens[$stackPtr]['content'] ), $stackPtr );
		}
	}

}

<?php
/**
 * WordPress-VIP-Minimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

/**
 * Restricts usage of rewrite rules flushing
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class WordPressVIPMinimum_Sniffs_VIP_FlushRewriteRulesSniff implements PHP_CodeSniffer_Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return PHP_CodeSniffer_Tokens::$functionNameTokens;
	}

	/**
	 * Process this test when one of its tokens is encountered.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile  The file being scanned.
	 * @param int                  $stackPtr   The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		$functionName = $tokens[ $stackPtr ]['content'];

		if ( 'flush_rules' !== $functionName ) {
			return;
		}

		$previousPtr = $phpcsFile->findPrevious(
			array_merge( PHP_CodeSniffer_Tokens::$emptyTokens ), // types.
			$stackPtr - 1, // start.
			null, // end.
			true, // exclude.
			null, // value.
			true // local.
		);

		if ( T_OBJECT_OPERATOR !== $tokens[ $previousPtr ]['code'] ) {
			return;
		}

		$previousPtr = $phpcsFile->findPrevious(
			array_merge( PHP_CodeSniffer_Tokens::$emptyTokens ), // types.
			$previousPtr - 1, // start.
			null, // end.
			true, // exclude.
			null, // value.
			true // local.
		);

		if ( T_VARIABLE !== $tokens[ $previousPtr ]['code'] && 'PHPCS_T_CLOSE_SQUARE_BRACKET' !== $tokens[ $previousPtr ]['code'] ) {
			return;
		}

		if ( 'PHPCS_T_CLOSE_SQUARE_BRACKET' === $tokens[ $previousPtr ]['code'] ) {
			$previousPtr = $phpcsFile->findPrevious(
				array_merge( PHP_CodeSniffer_Tokens::$emptyTokens ), // types.
				$previousPtr - 1, // start.
				null, // end.
				true, // exclude.
				null, // value.
				true // local.
			);
			if ( T_CONSTANT_ENCAPSED_STRING !== $tokens[ $previousPtr ]['code'] ) {
				return;
			}
		}

		if ( '$wp_rewrite' !== $tokens[ $previousPtr ]['content'] && '\'wp_rewrite\'' !== $tokens[ $previousPtr ]['content'] ) {
			return;
		}

		$phpcsFile->addError( sprintf( '%s should not be used in any normal circumstances in the theme code.', $tokens[ $stackPtr ]['content'] ), $stackPtr, 'WordPressVIPMinimum.VIP.FlushRewriteRules' );
	}

}

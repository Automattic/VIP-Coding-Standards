<?php
/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingNonPHPFileSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Files;

use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingNonPHPFileSniff.
 *
 * Checks that __DIR__, dirname( __FILE__ ) or plugin_dir_path( __FILE__ )
 * is used when including or requiring files.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class IncludingNonPHPFileSniff implements \PHP_CodeSniffer_Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return Tokens::$includeTokens;

	}//end register()


	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current token in the
	 *                                               stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$curStackPtr = $stackPtr;
		while ( $curStackPtr = $phpcsFile->findNext( Tokens::$stringTokens, ( $curStackPtr + 1 ), null, false, null, true ) ) {

			if ( T_CONSTANT_ENCAPSED_STRING === $tokens[ $curStackPtr ]['code'] ) {
				$stringWithoutEnclosingQuotationMarks = trim( $tokens[ $curStackPtr ]['content'], "\"'" );
			} else {
				$stringWithoutEnclosingQuotationMarks = $tokens[ $curStackPtr ]['content'];
			}

			$isFileName = preg_match( '/.*(\.[a-z]{2,})$/i', $stringWithoutEnclosingQuotationMarks, $regexMatches );

			if ( false === $isFileName || 0 === $isFileName ) {
				continue;
			}

			$extension = $regexMatches[1];
			if ( true === in_array( $extension, array( '.php', '.inc' ), true ) ) {
				return;
			}

			if ( true === in_array( $extension, array( '.svg', '.css' ), true ) ) {
				$phpcsFile->addError( sprintf( 'Local SVG and CSS files should be loaded via `get_file_contents` rather than via `%s`.', $tokens[ $stackPtr ]['content'] ), $curStackPtr, 'IncludingSVGCSSFile' );
			} else {
				$phpcsFile->addError( sprintf( 'Local non-PHP file should be loaded via `get_file_contents` rather than via `%s`', $tokens[ $stackPtr ]['content'] ), $curStackPtr, 'IncludingNonPHPFile' );
			}
		}

	}//end process()


}//end class

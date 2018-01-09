<?php
/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingFileSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\JS;

use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * WordPressVIPMinimum_Sniffs_JS_InnerHTMLSniff.
 *
 * Looks for instances of .innerHMTL.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class InnerHTMLSniff implements \PHP_CodeSniffer_Sniff {

	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var array
	 */
	public $supportedTokenizers = array(
		'JS',
	);

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(
			T_STRING,
		);

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

		if ( 'innerHTML' !== $tokens[ $stackPtr ]['content'] ) {
			// Looking for .innerHTML only.
			return;
		}

		$prevToken = $phpcsFile->findPrevious( Tokens::$emptyTokens, ( $stackPtr - 1 ), null, true, null, true );

		if ( T_OBJECT_OPERATOR !== $tokens[ $prevToken ]['code'] ) {
				return;
		}

		$nextToken = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true, null, true );

		if ( T_EQUAL !== $tokens[ $nextToken ]['code'] ) {
			// Not an assignment.
			return;
		}

		$nextToken     = $phpcsFile->findNext( Tokens::$emptyTokens, ( $nextToken + 1 ), null, true, null, true );
		$foundVariable = false;

		while ( false !== $nextToken && T_SEMICOLON !== $tokens[ $nextToken ]['code'] ) {

			if ( T_STRING === $tokens[ $nextToken ]['code'] ) {
				$foundVariable = true;
				break;
			}

			$nextToken = $phpcsFile->findNext( Tokens::$emptyTokens, ( $nextToken + 1 ), null, true, null, true );
		}

		if ( true === $foundVariable ) {
			$phpcsFile->addWarning( sprintf( 'Any HTML passed to %s gets executed. Consider using .textContent or make sure that used variables are properly escaped.', $tokens[ $stackPtr ]['content'] ), $stackPtr, $tokens[ $stackPtr ]['content'] );
		}

	}//end process()

}//end class

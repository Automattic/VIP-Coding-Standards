<?php
/**
 * WordPressVIPMinimum_Sniffs_JS_DangerouslySetInnerHTMLSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\JS;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * WordPressVIPMinimum_Sniffs_JS_DangerouslySetInnerHTMLSniff.
 *
 * Looks for instances of React's dangerouslySetInnerHMTL.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class DangerouslySetInnerHTMLSniff implements Sniff {

	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var string[]
	 */
	public $supportedTokenizers = [ 'JS' ];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [
			T_STRING,
		];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		if ( 'dangerouslySetInnerHTML' !== $tokens[ $stackPtr ]['content'] ) {
			// Looking for dangerouslySetInnerHTML only.
			return;
		}

		$nextToken = $phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true );

		if ( T_EQUAL !== $tokens[ $nextToken ]['code'] ) {
			// Not an assignment.
			return;
		}

		$nextNextToken = $phpcsFile->findNext( Tokens::$emptyTokens, $nextToken + 1, null, true, null, true );

		if ( T_OBJECT !== $tokens[ $nextNextToken ]['code'] ) {
			// Not react syntax.
			return;
		}

		$message = "Any HTML passed to `%s` gets executed. Please make sure it's properly escaped.";
		$data    = [ $tokens[ $stackPtr ]['content'] ];
		$phpcsFile->addError( $message, $stackPtr, 'Found', $data );
	}

}

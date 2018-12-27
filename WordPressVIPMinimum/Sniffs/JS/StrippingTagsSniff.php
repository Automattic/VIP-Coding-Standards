<?php
/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingFileSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\JS;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * WordPressVIPMinimum_Sniffs_JS_StrippingTagsSniff.
 *
 * Looks for incorrect way of stripping tags.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class StrippingTagsSniff implements Sniff {

	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var array
	 */
	public $supportedTokenizers = [
		'JS',
	];

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
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current token in the
	 *                                               stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		if ( 'html' !== $tokens[ $stackPtr ]['content'] ) {
			// Looking for html() only.
			return;
		}

		$nextToken = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true, null, true );

		if ( T_OPEN_PARENTHESIS !== $tokens[ $nextToken ]['code'] ) {
			// Not a function.
			return;
		}

		$afterFunctionCall = $phpcsFile->findNext( Tokens::$emptyTokens, ( $tokens[ $nextToken ]['parenthesis_closer'] + 1 ), null, true, null, true );

		if ( T_OBJECT_OPERATOR !== $tokens[ $afterFunctionCall ]['code'] ) {
			return;
		}

		$nextToken = $phpcsFile->findNext( Tokens::$emptyTokens, ( $afterFunctionCall + 1 ), null, true, null, true );

		if ( T_STRING === $tokens[ $nextToken ]['code'] && 'text' === $tokens[ $nextToken ]['content'] ) {
			$phpcsFile->addError( 'Vulnerable tag stripping approach detected', $stackPtr, 'VulnerableTagStripping' );
		}
	}

}

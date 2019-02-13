<?php
/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingFileSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\JS;

use PHP_CodeSniffer\Files\File;
use WordPressVIPMinimum\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * WordPressVIPMinimum_Sniffs_JS_StrippingTagsSniff.
 *
 * Looks for incorrect way of stripping tags.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class StrippingTagsSniff extends Sniff {

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
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		if ( 'html' !== $this->tokens[ $stackPtr ]['content'] ) {
			// Looking for html() only.
			return;
		}

		$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true );

		if ( T_OPEN_PARENTHESIS !== $this->tokens[ $nextToken ]['code'] ) {
			// Not a function.
			return;
		}

		$afterFunctionCall = $this->phpcsFile->findNext( Tokens::$emptyTokens, $this->tokens[ $nextToken ]['parenthesis_closer'] + 1, null, true, null, true );

		if ( T_OBJECT_OPERATOR !== $this->tokens[ $afterFunctionCall ]['code'] ) {
			return;
		}

		$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $afterFunctionCall + 1, null, true, null, true );

		if ( T_STRING === $this->tokens[ $nextToken ]['code'] && 'text' === $this->tokens[ $nextToken ]['content'] ) {
			$message = 'Vulnerable tag stripping approach detected.';
			$this->phpcsFile->addError( $message, $stackPtr, 'VulnerableTagStripping' );
		}
	}

}

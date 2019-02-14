<?php
/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingFileSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\JS;

use WordPressVIPMinimum\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * WordPressVIPMinimum_Sniffs_JS_StringConcatSniff.
 *
 * Looks for HTML string concatenation.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class StringConcatSniff extends Sniff {

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
			T_PLUS,
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

		$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true );

		if ( T_CONSTANT_ENCAPSED_STRING === $this->tokens[ $nextToken ]['code'] && false !== strpos( $this->tokens[ $nextToken ]['content'], '<' ) && 1 === preg_match( '/\<\/[a-zA-Z]+/', $this->tokens[ $nextToken ]['content'] ) ) {
			$data = [ '+' . $this->tokens[ $nextToken ]['content'] ];
			$this->addFoundError( $stackPtr, $data );
		}

		$prevToken = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $stackPtr - 1, null, true, null, true );

		if ( T_CONSTANT_ENCAPSED_STRING === $this->tokens[ $prevToken ]['code'] && false !== strpos( $this->tokens[ $prevToken ]['content'], '<' ) && 1 === preg_match( '/\<[a-zA-Z]+/', $this->tokens[ $prevToken ]['content'] ) ) {
			$data = [ $this->tokens[ $nextToken ]['content'] . '+' ];
			$this->addFoundError( $stackPtr, $data );
		}
	}

	/**
	 * Consolidated violation.
	 *
	 * @param int   $stackPtr  The position of the current token in the stack passed in $tokens.
	 * @param array $data      Replacements for the error message.
	 */
	private function addFoundError( $stackPtr, array $data ) {
		$message = 'HTML string concatenation detected, this is a security risk, use DOM node construction or a templating language instead: %s.';
		$this->phpcsFile->addError( $message, $stackPtr, 'Found', $data );
	}

}

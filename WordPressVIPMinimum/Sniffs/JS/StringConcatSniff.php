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
 * WordPressVIPMinimum_Sniffs_JS_StringConcatSniff.
 *
 * Looks for HTML string concatenation.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class StringConcatSniff implements Sniff {

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
			T_PLUS,
		];
	}


	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$nextToken = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true, null, true );

		if ( T_CONSTANT_ENCAPSED_STRING === $tokens[ $nextToken ]['code'] ) {
			if ( false !== strpos( $tokens[ $nextToken ]['content'], '<' ) && 1 === preg_match( '/\<\/[a-zA-Z]+/', $tokens[ $nextToken ]['content'] ) ) {
				$data = [ '+' . $tokens[ $nextToken ]['content'] ];
				$this->addFoundError( $phpcsFile, $stackPtr, $data );
			}
		}

		$prevToken = $phpcsFile->findPrevious( Tokens::$emptyTokens, ( $stackPtr - 1 ), null, true, null, true );

		if ( T_CONSTANT_ENCAPSED_STRING === $tokens[ $prevToken ]['code'] ) {
			if ( false !== strpos( $tokens[ $prevToken ]['content'], '<' ) && 1 === preg_match( '/\<[a-zA-Z]+/', $tokens[ $prevToken ]['content'] ) ) {
				$data = [ $tokens[ $nextToken ]['content'] . '+' ];
				$this->addFoundError( $phpcsFile, $stackPtr, $data );
			}
		}
	}

	/**
	 * Consolidated violation.
	 *
	 * @param File  $phpcsFile The file being scanned.
	 * @param int   $stackPtr  The position of the current token in the stack passed in $tokens.
	 * @param array $data     Replacements for the error message.
	 */
	private function addFoundError( File $phpcsFile, $stackPtr, array $data ) {
		$message = 'HTML string concatenation detected, this is a security risk, use DOM node construction or a templating language instead: %s.';
		$phpcsFile->addError( $message, $stackPtr, 'Found', $data );
	}

}

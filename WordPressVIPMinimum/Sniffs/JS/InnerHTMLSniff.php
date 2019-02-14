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
 * WordPressVIPMinimum_Sniffs_JS_InnerHTMLSniff.
 *
 * Looks for instances of .innerHMTL.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class InnerHTMLSniff extends Sniff {

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

		if ( 'innerHTML' !== $this->tokens[ $stackPtr ]['content'] ) {
			// Looking for .innerHTML only.
			return;
		}

		$prevToken = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $stackPtr - 1, null, true, null, true );

		if ( T_OBJECT_OPERATOR !== $this->tokens[ $prevToken ]['code'] ) {
				return;
		}

		$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true );

		if ( T_EQUAL !== $this->tokens[ $nextToken ]['code'] ) {
			// Not an assignment.
			return;
		}

		$nextToken     = $this->phpcsFile->findNext( Tokens::$emptyTokens, $nextToken + 1, null, true, null, true );
		$foundVariable = false;

		while ( false !== $nextToken && T_SEMICOLON !== $this->tokens[ $nextToken ]['code'] ) {

			if ( T_STRING === $this->tokens[ $nextToken ]['code'] ) {
				$foundVariable = true;
				break;
			}

			$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $nextToken + 1, null, true, null, true );
		}

		if ( true === $foundVariable ) {
			$message = 'Any HTML passed to `%s` gets executed. Consider using `.textContent` or make sure that used variables are properly escaped.';
			$data    = [ $this->tokens[ $stackPtr ]['content'] ];
			$this->phpcsFile->addWarning( $message, $stackPtr, 'Found', $data );
		}
	}

}

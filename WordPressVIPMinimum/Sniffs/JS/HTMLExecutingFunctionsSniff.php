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
 * WordPressVIPMinimum_Sniffs_JS_HTMLExecutingFunctions.
 *
 * Flags functions which are executing HTML passed to it.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class HTMLExecutingFunctionsSniff extends Sniff {

	/**
	 * List of HTML executing functions.
	 *
	 * @var array
	 */
	public $HTMLExecutingFunctions = [
		'html',
		'append',
		'write',
		'writeln',
	];

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

		if ( false === in_array( $this->tokens[ $stackPtr ]['content'], $this->HTMLExecutingFunctions, true ) ) {
			// Looking for specific functions only.
			return;
		}

		$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true );

		if ( T_OPEN_PARENTHESIS !== $this->tokens[ $nextToken ]['code'] ) {
			// Not a function.
			return;
		}

		$parenthesis_closer = $this->tokens[ $nextToken ]['parenthesis_closer'];

		while ( $nextToken < $parenthesis_closer ) {
			$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $nextToken + 1, null, true, null, true );
			if ( T_STRING === $this->tokens[ $nextToken ]['code'] ) {
				$message = 'Any HTML passed to `%s` gets executed. Make sure it\'s properly escaped.';
				$data    = [ $this->tokens[ $stackPtr ]['content'] ];
				$this->phpcsFile->addWarning( $message, $stackPtr, $this->tokens[ $stackPtr ]['content'], $data );

				return;
			}
		}
	}

}

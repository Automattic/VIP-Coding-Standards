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
 * WordPressVIPMinimum_Sniffs_JS_HTMLExecutingFunctions.
 *
 * Flags functions which are executing HTML passed to it.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class HTMLExecutingFunctionsSniff implements Sniff {

	/**
	 * List of HTML executing functions.
	 *
	 * @var array
	 */
	public $HTMLExecutingFunctions = array(
		'html',
		'append',
		'write',
		'writeln',
	);

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

		if ( false === in_array( $tokens[ $stackPtr ]['content'], $this->HTMLExecutingFunctions, true ) ) {
			// Looking for specific functions only.
			return;
		}

		$nextToken = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true, null, true );

		if ( T_OPEN_PARENTHESIS !== $tokens[ $nextToken ]['code'] ) {
			// Not a function.
			return;
		}

		$parenthesis_closer = $tokens[ $nextToken ]['parenthesis_closer'];

		while ( $nextToken < $parenthesis_closer ) {
			$nextToken = $phpcsFile->findNext( Tokens::$emptyTokens, ( $nextToken + 1 ), null, true, null, true );
			if ( T_STRING === $tokens[ $nextToken ]['code'] ) {
				$phpcsFile->addWarning( sprintf( 'Any HTML passed to `%s` gets executed. Make sure it\'s properly escaped.', $tokens[ $stackPtr ]['content'] ), $stackPtr, $tokens[ $stackPtr ]['content'] );
				return;
			}
		}
	}

}

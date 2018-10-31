<?php
/**
 * WordPressVIPMinimum_Sniffs_JS_WindowSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\JS;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * WordPressVIPMinimum_Sniffs_JS_WindowSniff.
 *
 * Looks for instances of window properties that should be flagged.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class WindowSniff implements Sniff {

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
		return [
			T_STRING,
		];
	}

	/**
	 * List of window properties that need to be flagged
	 *
	 * @var array
	 */
	private $windowProperties = [
		'location' => [
			'href' => true,
			'hostname' => true,
			'pathname' => true,
			'protocol' => true,
			'assign' => true,
		],
		'name' => true,
		'status' => true,
	];

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

		if ( 'window' !== $tokens[ $stackPtr ]['content'] ) {
			// if it doesn't begin with 'window', bail.
			return;
		}

		$nextToken = $phpcsFile->findNext( null, ( $stackPtr + 1 ), null, true, null, true );

		if ( T_OBJECT_OPERATOR !== $tokens[ $nextToken ]['code'] ) {
			// if there is no '.' next, bail.
			return;
		}

		$nextNextToken = $phpcsFile->findNext( null, ( $nextToken + 1 ), null, true, null, true );

		$nextNextNextToken = $phpcsFile->findNext( null, ( $nextNextToken + 1 ), null, true, null, true );

		if ( isset( $this->windowProperties[ $tokens[ $nextNextToken ]['content'] ] ) && T_OBJECT_OPERATOR !== $tokens[ $nextNextNextToken ]['code'] ) {
			$phpcsFile->addError( sprintf( 'Data from JS global "window.' . $tokens[ $nextNextToken ]['content'] . '" may contain user-supplied values and should be sanitized before output to prevent XSS.', $tokens[ $stackPtr ]['content'] ), $stackPtr, $tokens[ $nextNextToken ]['content'] );
		}

		if ( T_OBJECT_OPERATOR !== $tokens[ $nextNextNextToken ]['code'] ) {
			// if there is no '.' next, bail.
			return;
		}

		$nextNextNextNextToken = $phpcsFile->findNext( null, ( $nextNextNextToken + 1 ), null, true, null, true );

		if ( isset( $this->windowProperties[ $tokens[ $nextNextToken ]['content'] ][ $tokens[ $nextNextNextNextToken ]['content'] ] ) ) {
			$phpcsFile->addError( sprintf( 'Data from JS global "window.' . $tokens[ $nextNextToken ]['content'] . '.' . $tokens[ $nextNextNextNextToken ]['content'] . '" may contain user-supplied values and should be sanitized before output to prevent XSS.', $tokens[ $stackPtr ]['content'] ), $stackPtr, $tokens[ $nextNextToken ]['content'] . $tokens[ $nextNextNextNextToken ]['content'] );
		}
	}

}

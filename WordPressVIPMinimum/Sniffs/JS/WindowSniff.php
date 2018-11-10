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
	 * List of window properties that need to be flagged.
	 *
	 * @var array
	 */
	private $windowProperties = [
		'location' => [
			'href'     => true,
			'protocol' => true,
			'host'     => true,
			'hostname' => true,
			'pathname' => true,
			'protocol' => true,
			'search'   => true,
			'hash'     => true,
			'username' => true,
			'port'     => true,
			'password' => true,
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
			// Doesn't begin with 'window', bail.
			return;
		}

		$nextTokenPtr = $phpcsFile->findNext( null, ( $stackPtr + 1 ), null, true, null, true );

		if ( T_OBJECT_OPERATOR !== $tokens[ $nextTokenPtr ]['code'] ) {
			// No '.' next, bail.
			return;
		}

		$nextNextTokenPtr = $phpcsFile->findNext( null, ( $nextTokenPtr + 1 ), null, true, null, true );

		if ( ! isset( $this->windowProperties[ $tokens[ $nextNextTokenPtr ]['content'] ] ) ) {
			// Not in $windowProperties, bail.
			return;
		}

		$prevTokenPtr = $phpcsFile->findPrevious( Tokens::$emptyTokens, ( $stackPtr - 1 ), null, true, null, true );
		$nextNextNextTokenPtr = $phpcsFile->findNext( null, ( $nextNextTokenPtr + 1 ), null, true, null, true );
		$nextNextNextNextTokenPtr = $phpcsFile->findNext( null, ( $nextNextNextTokenPtr + 1 ), null, true, null, true );

		if ( T_OBJECT_OPERATOR === $tokens[ $nextNextNextTokenPtr ]['code'] && ! isset( $this->windowProperties[ $tokens[ $nextNextTokenPtr ]['content'] ][ $tokens[ $nextNextNextNextTokenPtr ]['content'] ] ) ) {
			// There is a '.' next but the property after is not in $windowProperties, bail.
			return;
		}

		$windowName = 'window.';
		$windowName .= ( isset( $this->windowProperties[ $tokens[ $nextNextTokenPtr ]['content'] ][ $tokens[ $nextNextNextNextTokenPtr ]['content'] ] ) ) ? $tokens[ $nextNextTokenPtr ]['content'] . $tokens[ $nextNextNextTokenPtr ]['content'] . $tokens[ $nextNextNextNextTokenPtr ]['content'] : $tokens[ $nextNextTokenPtr ]['content'];

		if ( T_EQUAL === $tokens[ $prevTokenPtr ]['code'] ) {
			// Variable assignment.
			$phpcsFile->addWarning( sprintf( 'Data from JS global "' . $windowName . '" may contain user-supplied values and should be checked.', $tokens[ $stackPtr ]['content'] ), $stackPtr, 'VarAssignment' );
			return;
		}

		$phpcsFile->addError( sprintf( 'Data from JS global "' . $windowName . '" may contain user-supplied values and should be sanitized before output to prevent XSS.', $tokens[ $stackPtr ]['content'] ), $stackPtr, $tokens[ $nextNextTokenPtr ]['content'] );
	}

}

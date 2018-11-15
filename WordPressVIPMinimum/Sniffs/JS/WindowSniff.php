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
		'name'   => true,
		'status' => true,
	];

	/**
	 * A list of tokens that are allowed in the syntax.
	 *
	 * @var array
	 */
	private $syntaxTokens = [
		T_OBJECT_OPERATOR,

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

		$nextTokenPtr = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true, null, true );
		$nextToken = $tokens[ $nextTokenPtr ]['code'];
		if ( T_OBJECT_OPERATOR !== $nextToken && T_OPEN_SQUARE_BRACKET !== $nextToken ) {
			// No . or [' next, bail.
			return;
		}

		$nextNextTokenPtr = $phpcsFile->findNext( Tokens::$emptyTokens, ( $nextTokenPtr + 1 ), null, true, null, true );
		if ( false === $nextNextTokenPtr ) {
			// Something went wrong, bail.
			return;
		}

		$nextNextToken = str_replace( [ '"', "'" ], '', $tokens[ $nextNextTokenPtr ]['content'] );
		if ( ! isset( $this->windowProperties[ $nextNextToken ] ) ) {
			// Not in $windowProperties, bail.
			return;
		}

		$nextNextNextTokenPtr = $phpcsFile->findNext( array_merge( [ T_CLOSE_SQUARE_BRACKET ], Tokens::$emptyTokens ), ( $nextNextTokenPtr + 1 ), null, true, null, true );
		$nextNextNextToken = $tokens[ $nextNextNextTokenPtr ]['code'];

		$nextNextNextNextToken = false;
		if ( T_OBJECT_OPERATOR === $nextNextNextToken || T_OPEN_SQUARE_BRACKET === $nextNextNextToken ) {
			$nextNextNextNextTokenPtr = $phpcsFile->findNext( Tokens::$emptyTokens, ( $nextNextNextTokenPtr + 1 ), null, true, null, true );
			if ( false === $nextNextNextNextTokenPtr ) {
				// Something went wrong, bail.
				return;
			}

			$nextNextNextNextToken = str_replace( [ '"', "'" ], '', $tokens[ $nextNextNextNextTokenPtr ]['content'] );
			if ( ! isset( $this->windowProperties[ $nextNextToken ][ $nextNextNextNextToken ] ) ) {
				// Not in $windowProperties, bail.
				return;
			}
		}

		$windowProperty = 'window.';
		$windowProperty .= $nextNextNextNextToken ? $nextNextToken . '.' . $nextNextNextNextToken : $nextNextToken;

		$prevTokenPtr = $phpcsFile->findPrevious( Tokens::$emptyTokens, ( $stackPtr - 1 ), null, true, null, true );
		if ( T_EQUAL === $tokens[ $prevTokenPtr ]['code'] ) {
			// Variable assignment.
			$phpcsFile->addWarning( sprintf( 'Data from JS global "' . $windowProperty . '" may contain user-supplied values and should be checked.', $tokens[ $stackPtr ]['content'] ), $stackPtr, 'VarAssignment' );
			return;
		}

		$phpcsFile->addError( sprintf( 'Data from JS global "' . $windowProperty . '" may contain user-supplied values and should be sanitized before output to prevent XSS.', $tokens[ $stackPtr ]['content'] ), $stackPtr, $nextNextToken );
	}

}

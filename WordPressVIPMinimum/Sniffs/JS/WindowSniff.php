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
			'search'   => true,
			'hash'     => true,
			'username' => true,
			'port'     => true,
			'password' => true,
		],
		'name'     => true,
		'status'   => true,
	];

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
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
		$nextToken    = $tokens[ $nextTokenPtr ]['code'];
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
		$nextNextNextToken    = $tokens[ $nextNextNextTokenPtr ]['code'];

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

		$windowProperty  = 'window.';
		$windowProperty .= $nextNextNextNextToken ? $nextNextToken . '.' . $nextNextNextNextToken : $nextNextToken;
		$data            = [ $windowProperty ];

		$prevTokenPtr = $phpcsFile->findPrevious( Tokens::$emptyTokens, ( $stackPtr - 1 ), null, true, null, true );

		if ( T_EQUAL === $tokens[ $prevTokenPtr ]['code'] ) {
			// Variable assignment.
			$message = 'Data from JS global "%s" may contain user-supplied values and should be checked.';
			$phpcsFile->addWarning( $message, $stackPtr, 'VarAssignment', $data );

			return;
		}

		$message = 'Data from JS global "%s" may contain user-supplied values and should be sanitized before output to prevent XSS.';
		$phpcsFile->addError( $message, $stackPtr, $nextNextToken, $data );
	}

}

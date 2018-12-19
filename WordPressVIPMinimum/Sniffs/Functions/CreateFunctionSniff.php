<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Functions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * This function generates an error when
 * create_function() is found in code.
 * create_function() is deprecated in PHP 7.2.0.
 *
 * An example:
 *
 * <code>
 *   create_function( 'foo', 'return "bar";' );
 * </code>
 *
 * See here: http://php.net/manual/en/function.create-function.php
 */
class CreateFunctionSniff implements Sniff {
	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * We want everything function-related.
	 *
	 * @return array(int)
	 */
	public function register() {
		return [ T_STRING ];
	}

	/**
	 * Processes the tokens that this sniff is interested in.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file where the token was found.
	 * @param int                         $stackPtr  The position in the stack where
	 *                                               the token was found.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens    = $phpcsFile->getTokens();
		$phpcsFile = $phpcsFile;
		$stackPtr  = $stackPtr;

		$functionName = $phpcsFile->findNext(
			T_STRING,
			( $stackPtr ),
			null,
			false,
			null,
			true
		);

		if ( 'create_function' !==
			$tokens[ $stackPtr ]['content']
		) {
			return;
		}

		// Check if this is really a function.
		$bracket = $phpcsFile->findNext(
			T_WHITESPACE,
			( $functionName + 1 ),
			null,
			true
		);

		if (
			( false === $bracket ) ||
			( T_OPEN_PARENTHESIS !== $tokens[ $bracket ]['code'] )
		) {
			return;
		}

		$phpcsFile->addError(
			'create_function() is deprecated as of PHP 7.2.0.',
			$functionName,
			'CreateFunction'
		);
	}
}

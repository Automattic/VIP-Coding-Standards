<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Functions;

use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

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
class CreateFunctionSniff implements \PHP_CodeSniffer_Sniff {
	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * We want everything function-related.
	 *
	 * @return array(int)
	 */
	public function register() {
		return Tokens::$functionNameTokens;

	}

	/**
	 * Processes the tokens that this sniff is interested in.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file where the token was found.
	 * @param int $stackPtr                          The position in the stack where
	 *                                               the token was found.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$this->_tokens	  = $phpcsFile->getTokens();
		$this->_phpcsFile = $phpcsFile;
		$this->_stackPtr  = $stackPtr;

		$functionName = $phpcsFile->findNext(
			T_STRING,
			( $stackPtr ),
			null,
			false,
			null,
			true
		);

		while ( false !== $functionName ) {
			if (
				( 'T_STRING' !==
					$this->_tokens[ $this->_stackPtr ]['type'] )
				||
				( 'create_function' !==
					$this->_tokens[ $this->_stackPtr ]['content'] )
			) {
				// Not a function call, repeat.
				$functionName = $phpcsFile->findNext(
					T_STRING,
					( $functionName + 1 ),
					null,
					false,
					null,
					true
				);

				continue;
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
				( T_OPEN_PARENTHESIS !== $this->_tokens[ $bracket ]['code'] )
			) {
				// Not a function call, repeat.
				$functionName = $phpcsFile->findNext(
					T_STRING,
					( $functionName + 1 ),
					null,
					false,
					null,
					true
				);

				continue;
			}

			$this->_phpcsFile->addError(
				'create_function() is deprecated as of ' .
					'PHP 7.2.0',
				$functionName,
				'CreateFunction'
			);

			break;
		}
	}
}

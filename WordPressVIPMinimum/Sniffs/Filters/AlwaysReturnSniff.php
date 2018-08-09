<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Filters;

use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * This sniff validates that filters always return a value
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class AlwaysReturnSniff implements \PHP_CodeSniffer_Sniff {

	/**
	 * The tokens of the phpcsFile.
	 *
	 * @var array
	 */
	private $tokens;

	/**
	 * The phpcsFile.
	 *
	 * @var phpcsFile
	 */
	private $phpcsFile;

	/**
	 * Filter name pointer.
	 *
	 * @var int
	 */
	private $filterNamePtr;

	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * @return array(int)
	 */
	public function register() {
		return Tokens::$functionNameTokens;

	}//end register()

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

		$this->tokens = $phpcsFile->getTokens();

		$this->phpcsFile = $phpcsFile;

		$functionName = $this->tokens[ $stackPtr ]['content'];

		if ( 'add_filter' !== $functionName ) {
			return;
		}

		$this->filterNamePtr = $this->phpcsFile->findNext(
			array_merge( Tokens::$emptyTokens, array( T_OPEN_PARENTHESIS ) ), // types.
			$stackPtr + 1, // start.
			null, // end.
			true, // exclude.
			null, // value.
			true // local.
		);

		if ( ! $this->filterNamePtr ) {
			// Something is wrong.
			return;
		}

		$callbackPtr = $this->phpcsFile->findNext(
			array_merge( Tokens::$emptyTokens, array( T_COMMA ) ), // types.
			$this->filterNamePtr + 1, // start.
			null, // end.
			true, // exclude.
			null, // value.
			true // local.
		);

		if ( ! $callbackPtr ) {
			// Something is wrong.
			return;
		}

		if ( 'PHPCS_T_CLOSURE' === $this->tokens[ $callbackPtr ]['code'] ) {
			$this->processFunctionBody( $callbackPtr );
		} elseif ( 'T_ARRAY' === $this->tokens[ $callbackPtr ]['type'] ) {
			$this->processArray( $callbackPtr );
		} elseif ( true === in_array( $this->tokens[ $callbackPtr ]['code'], Tokens::$stringTokens, true ) ) {
			$this->processString( $callbackPtr );
		}

	}

	/**
	 * Process array.
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 */
	private function processArray( $stackPtr ) {

		$previous = $this->phpcsFile->findPrevious(
			Tokens::$emptyTokens, // types.
			$this->tokens[ $stackPtr ]['parenthesis_closer'] - 1, // start.
			null, // end.
			true, // exclude.
			null, // value.
			false // local.
		);

		if ( true === in_array( T_CLASS, $this->tokens[ $stackPtr ]['conditions'], true ) ) {
			$classPtr = array_search( T_CLASS, $this->tokens[ $stackPtr ]['conditions'], true );
			if ( $classPtr ) {
				$classToken = $this->tokens[ $classPtr ];
				$this->processString( $previous, $classToken['scope_opener'], $classToken['scope_closer'] );
				return;
			}
		}

		$this->processString( $previous );

	}

	/**
	 * Process string.
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 */
	private function processString( $stackPtr, $start = 0, $end = null ) {

		$callbackFunctionName = substr( $this->tokens[ $stackPtr ]['content'], 1, -1 );

		$callbackFunctionPtr = $this->phpcsFile->findNext(
			Tokens::$functionNameTokens, // types.
			$start, // start.
			$end, // end.
			false, // exclude.
			$callbackFunctionName, // value.
			false // local.
		);

		if ( ! $callbackFunctionPtr ) {
			// We were not able to find the function callback in the file.
			return;
		}

		$this->processFunction( $callbackFunctionPtr, $start, $end );

	}

	/**
	 * Process function.
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 */
	private function processFunction( $stackPtr, $start = 0, $end = null ) {

		$functionName = $this->tokens[ $stackPtr ]['content'];

		$offset = $start;
		while( $functionStackPtr = $this->phpcsFile->findNext( array( T_FUNCTION ), $offset, $end, false, null, false ) ) {
			$functionNamePtr = $this->phpcsFile->findNext( Tokens::$emptyTokens, $functionStackPtr + 1, null, true, null, true );
			if ( T_STRING === $this->tokens[ $functionNamePtr ]['code'] ) {
				if ( $this->tokens[ $functionNamePtr ]['content'] === $functionName ) {
					$this->processFunctionBody( $functionStackPtr );
					return;
				}
			}
			$offset = $functionStackPtr + 1;
		}
	}

	/**
	 * Process function's body
	 *
	 * @param int    $stackPtr The position in the stack where the token was found.
	 * @param string $variableName Variable name.
	 */
	private function processFunctionBody( $stackPtr ) {

		$filterName = $this->tokens[ $this->filterNamePtr ]['content'];

		$functionBodyScopeStart = $this->tokens[ $stackPtr ]['scope_opener'];
		$functionBodyScopeEnd   = $this->tokens[ $stackPtr ]['scope_closer'];

		$returnTokenPtr = $this->phpcsFile->findNext(
			array( T_RETURN ), // types.
			( $functionBodyScopeStart + 1 ), // start.
			$functionBodyScopeEnd, // end.
			false, // exclude.
			null, // value.
			false // local.
		);

		$insideIfConditionalReturn = 0;
		$outsideConditionalReturn = 0;

		while ( $returnTokenPtr ) {
			if ( $this->isInsideIfConditonal( $returnTokenPtr ) ) {
				$insideIfConditionalReturn++;
			} else {
				$outsideConditionalReturn++;
			}
			if ( $this->isReturningVoid( $returnTokenPtr ) ) {
				$this->phpcsFile->AddWarning( sprintf( 'Please, make sure that a callback to `%s` filter is returnin void intentionally.', $filterName ), $functionBodyScopeStart, 'voidReturn' );
			}
			$returnTokenPtr = $this->phpcsFile->findNext(
				array( T_RETURN ), // types.
				( $returnTokenPtr + 1 ), // start.
				$functionBodyScopeEnd, // end.
				false, // exclude.
				null, // value.
				false // local.
			);
		}

		if ( $insideIfConditionalReturn > 0 && $outsideConditionalReturn === 0 ) {
			$this->phpcsFile->AddWarning( sprintf( 'Please, make sure that a callback to `%s` filter is always returning some value.', $filterName ), $functionBodyScopeStart, 'missingReturnStatement' );
		}

	}

	/**
	 * Is the current token inside a conditional?
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 *
	 * @return bool
	 */
	private function isInsideIfConditonal( $stackPtr ) {

		// This check helps us in situations a class or a function is wrapped
		// inside a conditional as a whole. Eg.: inside `class_exists`.
		if ( T_FUNCTION === end( $this->tokens[ $stackPtr ]['conditions'] ) ) {
			return false;
		}

		// Similar case may be a conditional closure
		if ( 'PHPCS_T_CLOSURE' === end( $this->tokens[ $stackPtr ]['conditions'] ) ) {
			return false;
		}

		// Loop over the array of conditions and look for an IF.
		reset( $this->tokens[ $stackPtr ]['conditions'] );

		if ( true === array_key_exists( 'conditions', $this->tokens[ $stackPtr ] )
		     && true === is_array( $this->tokens[ $stackPtr ]['conditions'] )
		     && false === empty( $this->tokens[ $stackPtr ]['conditions'] )
		) {
			foreach( $this->tokens[ $stackPtr ]['conditions'] as $tokenPtr => $tokenCode ) {
				if ( T_IF === $this->tokens[ $stackPtr ]['conditions'][ $tokenPtr ] ) {
					return true;
				}
			}
		}
		return false;
	}

	private function isReturningVoid( $stackPtr ) {

		$nextToReturnTokenPtr = $this->phpcsFile->findNext(
			array( Tokens::$emptyTokens ), // types.
			( $stackPtr + 1 ), // start.
			null, // end.
			true, // exclude.
			null, // value.
			false // local.
		);

		if ( T_SEMICOLON === $this->tokens[ $nextToReturnTokenPtr ]['code'] ) {
			return true;
		}

		return false;
	}
}

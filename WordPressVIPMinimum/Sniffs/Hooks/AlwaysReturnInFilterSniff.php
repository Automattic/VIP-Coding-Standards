<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Hooks;

use WordPressVIPMinimum\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * This sniff validates that filters always return a value
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class AlwaysReturnInFilterSniff extends Sniff {

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
	}

	/**
	 * Processes the tokens that this sniff is interested in.
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		$functionName = $this->tokens[ $stackPtr ]['content'];

		if ( 'add_filter' !== $functionName ) {
			return;
		}

		$this->filterNamePtr = $this->phpcsFile->findNext(
			array_merge( Tokens::$emptyTokens, [ T_OPEN_PARENTHESIS ] ),
			$stackPtr + 1,
			null,
			true,
			null,
			true
		);

		if ( ! $this->filterNamePtr ) {
			// Something is wrong.
			return;
		}

		$callbackPtr = $this->phpcsFile->findNext(
			array_merge( Tokens::$emptyTokens, [ T_COMMA ] ),
			$this->filterNamePtr + 1,
			null,
			true,
			null,
			true
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
			Tokens::$emptyTokens,
			$this->tokens[ $stackPtr ]['parenthesis_closer'] - 1,
			null,
			true
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
	 * @param int $start The start of the token.
	 * @param int $end The end of the token.
	 */
	private function processString( $stackPtr, $start = 0, $end = null ) {

		$callbackFunctionName = substr( $this->tokens[ $stackPtr ]['content'], 1, -1 );

		$callbackFunctionPtr = $this->phpcsFile->findNext(
			Tokens::$functionNameTokens,
			$start,
			$end,
			false,
			$callbackFunctionName
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
	 * @param int $start The start of the token.
	 * @param int $end The end of the token.
	 */
	private function processFunction( $stackPtr, $start = 0, $end = null ) {

		$functionName = $this->tokens[ $stackPtr ]['content'];

		$offset = $start;
		while ( false !== $this->phpcsFile->findNext( [ T_FUNCTION ], $offset, $end ) ) {
			$functionStackPtr = $this->phpcsFile->findNext( [ T_FUNCTION ], $offset, $end );
			$functionNamePtr  = $this->phpcsFile->findNext( Tokens::$emptyTokens, $functionStackPtr + 1, null, true, null, true );
			if ( T_STRING === $this->tokens[ $functionNamePtr ]['code'] && $this->tokens[ $functionNamePtr ]['content'] === $functionName ) {
				$this->processFunctionBody( $functionStackPtr );
				return;
			}
			$offset = $functionStackPtr + 1;
		}
	}

	/**
	 * Process function's body
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 */
	private function processFunctionBody( $stackPtr ) {

		$argPtr = $this->phpcsFile->findNext(
			array_merge( Tokens::$emptyTokens, [ T_STRING, T_OPEN_PARENTHESIS ] ),
			$stackPtr + 1,
			null,
			true,
			null,
			true
		);

		// If arg is being passed by reference, we can skip.
		if ( T_BITWISE_AND === $this->tokens[ $argPtr ]['code'] ) {
			return;
		}

		$filterName = $this->tokens[ $this->filterNamePtr ]['content'];

		$functionBodyScopeStart = $this->tokens[ $stackPtr ]['scope_opener'];
		$functionBodyScopeEnd   = $this->tokens[ $stackPtr ]['scope_closer'];

		$returnTokenPtr = $this->phpcsFile->findNext(
			[ T_RETURN ],
			$functionBodyScopeStart + 1,
			$functionBodyScopeEnd
		);

		$insideIfConditionalReturn = 0;
		$outsideConditionalReturn  = 0;

		while ( $returnTokenPtr ) {
			if ( $this->isInsideIfConditonal( $returnTokenPtr ) ) {
				$insideIfConditionalReturn++;
			} else {
				$outsideConditionalReturn++;
			}
			if ( $this->isReturningVoid( $returnTokenPtr ) ) {
				$message = 'Please, make sure that a callback to `%s` filter is returning void intentionally.';
				$data    = [ $filterName ];
				$this->phpcsFile->addError( $message, $functionBodyScopeStart, 'VoidReturn', $data );
			}
			$returnTokenPtr = $this->phpcsFile->findNext(
				[ T_RETURN ],
				$returnTokenPtr + 1,
				$functionBodyScopeEnd
			);
		}

		if ( 0 <= $insideIfConditionalReturn && 0 === $outsideConditionalReturn ) {
			$message = 'Please, make sure that a callback to `%s` filter is always returning some value.';
			$data    = [ $filterName ];
			$this->phpcsFile->addError( $message, $functionBodyScopeStart, 'MissingReturnStatement', $data );

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

		// Similar case may be a conditional closure.
		if ( 'PHPCS_T_CLOSURE' === end( $this->tokens[ $stackPtr ]['conditions'] ) ) {
			return false;
		}

		// Loop over the array of conditions and look for an IF.
		reset( $this->tokens[ $stackPtr ]['conditions'] );

		if ( true === array_key_exists( 'conditions', $this->tokens[ $stackPtr ] )
			&& true === is_array( $this->tokens[ $stackPtr ]['conditions'] )
			&& false === empty( $this->tokens[ $stackPtr ]['conditions'] )
		) {
			foreach ( $this->tokens[ $stackPtr ]['conditions'] as $tokenPtr => $tokenCode ) {
				if ( T_IF === $this->tokens[ $stackPtr ]['conditions'][ $tokenPtr ] ) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Is the token returning void
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 *
	 * @return bool
	 **/
	private function isReturningVoid( $stackPtr ) {

		$nextToReturnTokenPtr = $this->phpcsFile->findNext(
			[ Tokens::$emptyTokens ],
			$stackPtr + 1,
			null,
			true
		);

		return T_SEMICOLON === $this->tokens[ $nextToReturnTokenPtr ]['code'];
	}
}

<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Performance;

use WordPressVIPMinimum\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * This sniff enforces checking the return value of a function before passing it to another one.
 *
 * An example of a not checking return value is:
 *
 * <code>
 * echo esc_url( wpcom_vip_get_term_link( $term ) );
 * </code>
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class CacheValueOverrideSniff extends Sniff {

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

		if ( 'wp_cache_get' !== $functionName ) {
			// Not a function we are looking for.
			return;
		}

		if ( false === $this->isFunctionCall( $stackPtr ) ) {
			// Not a function call.
			return;
		}

		$variablePos = $this->isVariableAssignment( $stackPtr );

		if ( false === $variablePos ) {
			// Not a variable assignment.
			return;
		}

		$variableToken = $this->tokens[ $variablePos ];
		$variableName  = $variableToken['content'];

		// Find the next non-empty token.
		$openBracket = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true );

		// Find the closing bracket.
		$closeBracket = $this->tokens[ $openBracket ]['parenthesis_closer'];

		$nextVariableOccurrence = $this->phpcsFile->findNext( T_VARIABLE, $closeBracket + 1, null, false, $variableName );

		$rightAfterNextVariableOccurence = $this->phpcsFile->findNext( Tokens::$emptyTokens, $nextVariableOccurrence + 1, null, true, null, true );

		if ( T_EQUAL !== $this->tokens[ $rightAfterNextVariableOccurence ]['code'] ) {
			// Not a value override.
			return;
		}

		$valueAfterEqualSign = $this->phpcsFile->findNext( Tokens::$emptyTokens, $rightAfterNextVariableOccurence + 1, null, true, null, true );

		if ( T_FALSE === $this->tokens[ $valueAfterEqualSign ]['code'] ) {
			$message = 'Obtained cached value in `%s` is being overridden. Disabling caching?';
			$data    = [ $variableName ];
			$this->phpcsFile->addError( $message, $nextVariableOccurrence, 'CacheValueOverride', $data );
		}
	}

	/**
	 * Check whether the examined code is a function call.
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return bool
	 */
	private function isFunctionCall( $stackPtr ) {

		if ( false === in_array( $this->tokens[ $stackPtr ]['code'], Tokens::$functionNameTokens, true ) ) {
			return false;
		}

		// Find the next non-empty token.
		$openBracket = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true );

		if ( T_OPEN_PARENTHESIS !== $this->tokens[ $openBracket ]['code'] ) {
			// Not a function call.
			return false;
		}

		// Find the previous non-empty token.
		$search   = Tokens::$emptyTokens;
		$search[] = T_BITWISE_AND;
		$previous = $this->phpcsFile->findPrevious( $search, $stackPtr - 1, null, true );

		// It's a function definition, not a function call, so return false.
		return ! ( T_FUNCTION === $this->tokens[ $previous ]['code'] );
	}

	/**
	 * Check whether the examined code is a variable assignment.
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return bool
	 */
	private function isVariableAssignment( $stackPtr ) {

		// Find the previous non-empty token.
		$search   = Tokens::$emptyTokens;
		$search[] = T_BITWISE_AND;
		$previous = $this->phpcsFile->findPrevious( $search, $stackPtr - 1, null, true );

		if ( T_EQUAL !== $this->tokens[ $previous ]['code'] ) {
			// It's not a variable assignment.
			return false;
		}

		$previous = $this->phpcsFile->findPrevious( $search, $previous - 1, null, true );

		if ( T_VARIABLE !== $this->tokens[ $previous ]['code'] ) {
			// It's not a variable assignment.
			return false;
		}

		return $previous;
	}

}

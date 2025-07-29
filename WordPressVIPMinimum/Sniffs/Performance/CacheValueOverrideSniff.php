<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Performance;

use PHP_CodeSniffer\Util\Tokens;
use WordPressCS\WordPress\AbstractFunctionRestrictionsSniff;

/**
 * This sniff check whether a cached value is being overridden.
 */
class CacheValueOverrideSniff extends AbstractFunctionRestrictionsSniff {

	/**
	 * Groups of functions to restrict.
	 *
	 * @return array<string, array<string, array<string>>>
	 */
	public function getGroups() {
		return [
			'wp_cache_get' => [
				'functions' => [ 'wp_cache_get' ],
			],
		];
	}

	/**
	 * Process a matched token.
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param string $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (function name) which was matched
	 *                                in lowercase.
	 *
	 * @return void
	 */
	public function process_matched_token( $stackPtr, $group_name, $matched_content ) {
		$variablePos = $this->isVariableAssignment( $stackPtr );
		if ( $variablePos === false ) {
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

		if ( $this->tokens[ $rightAfterNextVariableOccurence ]['code'] !== T_EQUAL ) {
			// Not a value override.
			return;
		}

		$valueAfterEqualSign = $this->phpcsFile->findNext( Tokens::$emptyTokens, $rightAfterNextVariableOccurence + 1, null, true, null, true );

		if ( $this->tokens[ $valueAfterEqualSign ]['code'] === T_FALSE ) {
			$message = 'Obtained cached value in `%s` is being overridden. Disabling caching?';
			$data    = [ $variableName ];
			$this->phpcsFile->addError( $message, $nextVariableOccurrence, 'CacheValueOverride', $data );
		}
	}

	/**
	 * Check whether the examined code is a variable assignment.
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return int|false
	 */
	private function isVariableAssignment( $stackPtr ) {

		// Find the previous non-empty token, but allow for FQN function calls.
		$search   = Tokens::$emptyTokens;
		$search[] = T_BITWISE_AND;
		$search[] = T_NS_SEPARATOR;
		$previous = $this->phpcsFile->findPrevious( $search, $stackPtr - 1, null, true );

		if ( $this->tokens[ $previous ]['code'] !== T_EQUAL ) {
			// It's not a variable assignment.
			return false;
		}

		$previous = $this->phpcsFile->findPrevious( $search, $previous - 1, null, true );

		if ( $this->tokens[ $previous ]['code'] !== T_VARIABLE ) {
			// It's not a variable assignment.
			return false;
		}

		return $previous;
	}
}

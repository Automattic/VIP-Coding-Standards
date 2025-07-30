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
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Conditions;
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
		$openBracket = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );
		if ( $openBracket === false || isset( $this->tokens[ $openBracket ]['parenthesis_closer'] ) === false ) {
			// Import use statement for function or parse error/live coding. Ignore.
			return;
		}

		$closeBracket  = $this->tokens[ $openBracket ]['parenthesis_closer'];
		$firstNonEmpty = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $openBracket + 1 ), null, true );
		$nextNonEmpty  = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $firstNonEmpty + 1 ), null, true );
		if ( $nextNonEmpty === false ) {
			// Parse error/live coding. Ignore.
			return;
		}

		if ( $this->tokens[ $firstNonEmpty ]['code'] === T_ELLIPSIS
			&& $nextNonEmpty === $closeBracket
		) {
			// First class callable. Ignore.
			return;
		}

		$variablePos = $this->isVariableAssignment( $stackPtr );
		if ( $variablePos === false ) {
			// Not a variable assignment.
			return;
		}

		$variableToken = $this->tokens[ $variablePos ];
		$variableName  = $variableToken['content'];

		// Figure out the scope we need to search in.
		$searchEnd   = $this->phpcsFile->numTokens;
		$functionPtr = Conditions::getLastCondition( $this->phpcsFile, $stackPtr, [ T_FUNCTION, T_CLOSURE ] );
		if ( $functionPtr !== false && isset( $this->tokens[ $functionPtr ]['scope_closer'] ) ) {
			$searchEnd = $this->tokens[ $functionPtr ]['scope_closer'];
		}

		$nextVariableOccurrence = false;
		for ( $i = $closeBracket + 1; $i < $searchEnd; $i++ ) {
			if ( $this->tokens[ $i ]['code'] === T_VARIABLE && $this->tokens[ $i ]['content'] === $variableName ) {
				$nextVariableOccurrence = $i;
				break;
			}

			// Skip over any and all closed scopes.
			if ( isset( Collections::closedScopes()[ $this->tokens[ $i ]['code'] ] ) ) {
				if ( isset( $this->tokens[ $i ]['scope_closer'] ) ) {
					$i = $this->tokens[ $i ]['scope_closer'];
				}
			}
		}

		if ( $nextVariableOccurrence === false ) {
			return;
		}

		$rightAfterNextVariableOccurence = $this->phpcsFile->findNext( Tokens::$emptyTokens, $nextVariableOccurrence + 1, $searchEnd, true, null, true );

		if ( $rightAfterNextVariableOccurence === false
			|| $this->tokens[ $rightAfterNextVariableOccurence ]['code'] !== T_EQUAL
		) {
			// Not a value override.
			return;
		}

		$valueAfterEqualSign = $this->phpcsFile->findNext( Tokens::$emptyTokens, $rightAfterNextVariableOccurence + 1, $searchEnd, true, null, true );

		if ( $valueAfterEqualSign !== false && $this->tokens[ $valueAfterEqualSign ]['code'] === T_FALSE ) {
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

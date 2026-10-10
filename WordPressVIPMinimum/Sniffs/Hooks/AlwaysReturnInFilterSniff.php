<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Hooks;

use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Arrays;
use PHPCSUtils\Utils\Conditions;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\TextStrings;
use WordPressVIPMinimum\Sniffs\Sniff;

/**
 * This sniff validates that filters always return a value
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
	 * @return array<int|string>
	 */
	public function register() {
		return [ T_STRING ];
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

		if ( $functionName !== 'add_filter' ) {
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

		if ( $this->tokens[ $callbackPtr ]['code'] === T_CLOSURE ) {
			$this->processFunctionBody( $callbackPtr );
		} elseif ( $this->tokens[ $callbackPtr ]['code'] === T_FN ) {
			// Arrow functions always return a value implicitly. No check needed.
			return;
		} elseif ( $this->tokens[ $callbackPtr ]['code'] === T_ARRAY
			|| $this->tokens[ $callbackPtr ]['code'] === T_OPEN_SHORT_ARRAY
		) {
			$this->processArray( $callbackPtr );
		} elseif ( in_array( $this->tokens[ $callbackPtr ]['code'], Tokens::$stringTokens, true ) === true ) {
			$this->processString( $callbackPtr );
		}
	}

	/**
	 * Process array.
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 *
	 * @return void
	 */
	private function processArray( $stackPtr ): void {

		$open_close = Arrays::getOpenClose( $this->phpcsFile, $stackPtr );
		if ( $open_close === false ) {
			return;
		}

		$previous = $this->phpcsFile->findPrevious(
			Tokens::$emptyTokens,
			$open_close['closer'] - 1,
			null,
			true
		);

		if ( in_array( T_CLASS, $this->tokens[ $stackPtr ]['conditions'], true ) === true ) {
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
	 * @param int $start    The start of the token.
	 * @param int $end      The end of the token.
	 *
	 * @return void
	 */
	private function processString( $stackPtr, $start = 0, $end = null ): void {

		$callbackFunctionName = TextStrings::stripQuotes( $this->tokens[ $stackPtr ]['content'] );

		$callbackFunctionPtr = $this->phpcsFile->findNext(
			T_STRING,
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
	 * @param int $start    The start of the token.
	 * @param int $end      The end of the token.
	 *
	 * @return void
	 */
	private function processFunction( $stackPtr, $start = 0, $end = null ): void {

		$functionName = $this->tokens[ $stackPtr ]['content'];

		$offset = $start;
		// phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition -- Valid usage.
		while ( ( $functionStackPtr = $this->phpcsFile->findNext( T_FUNCTION, $offset, $end ) ) !== false ) {
			$declaredName = FunctionDeclarations::getName( $this->phpcsFile, $functionStackPtr );
			if ( $declaredName === $functionName ) {
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
	 *
	 * @return void
	 */
	private function processFunctionBody( $stackPtr ): void {

		$filterName = $this->tokens[ $this->filterNamePtr ]['content'];

		$methodProps = FunctionDeclarations::getProperties( $this->phpcsFile, $stackPtr );
		if ( $methodProps['is_abstract'] === true ) {
			$message = 'The callback for the `%s` filter hook-in points to an abstract method. Please ensure that child class implementations of this method always return a value.';
			$data    = [ $filterName ];
			$this->phpcsFile->addWarning( $message, $stackPtr, 'AbstractMethod', $data );
			return;
		}

		if ( isset( $this->tokens[ $stackPtr ]['scope_opener'], $this->tokens[ $stackPtr ]['scope_closer'] ) === false ) {
			// Live coding, parse or tokenizer error.
			return;
		}

		$argPtr = $this->phpcsFile->findNext(
			array_merge( Tokens::$emptyTokens, [ T_STRING, T_OPEN_PARENTHESIS ] ),
			$stackPtr + 1,
			null,
			true,
			null,
			true
		);

		// If arg is being passed by reference, we can skip.
		if ( $this->tokens[ $argPtr ]['code'] === T_BITWISE_AND ) {
			return;
		}

		$functionBodyScopeStart = $this->tokens[ $stackPtr ]['scope_opener'];
		$functionBodyScopeEnd   = $this->tokens[ $stackPtr ]['scope_closer'];

		$returnTokenPtr = $this->phpcsFile->findNext(
			[ T_RETURN ],
			$functionBodyScopeStart + 1,
			$functionBodyScopeEnd
		);

		while ( $returnTokenPtr ) {
			// A return in a nested closure or function does not return from the callback.
			if ( Conditions::getLastCondition( $this->phpcsFile, $returnTokenPtr, [ T_FUNCTION, T_CLOSURE ] ) === $stackPtr ) {
				if ( $this->isReturningVoid( $returnTokenPtr ) ) {
					$message = 'Please, make sure that a callback to `%s` filter is returning void intentionally.';
					$data    = [ $filterName ];
					$this->phpcsFile->addError( $message, $functionBodyScopeStart, 'VoidReturn', $data );
				}
			}
			$returnTokenPtr = $this->phpcsFile->findNext(
				[ T_RETURN ],
				$returnTokenPtr + 1,
				$functionBodyScopeEnd
			);
		}

		if ( $this->alwaysReturns( $functionBodyScopeStart, $functionBodyScopeEnd ) === false ) {
			if ( $this->hasTerminatingStatement( $functionBodyScopeStart, $functionBodyScopeEnd ) ) {
				$message = 'The callback for the `%s` filter uses a terminating statement (`exit`, `die`, or `throw`) instead of returning a value. Filter callbacks should always return a value.';
				$data    = [ $filterName ];
				$this->phpcsFile->addWarning( $message, $functionBodyScopeStart, 'TerminatingInsteadOfReturn', $data );
			} else {
				$message = 'Please, make sure that a callback to `%s` filter is always returning some value.';
				$data    = [ $filterName ];
				$this->phpcsFile->addError( $message, $functionBodyScopeStart, 'MissingReturnStatement', $data );
			}
		}
	}

	/**
	 * Does every path through the code between the scope opener and closer reach a return?
	 *
	 * Only if, elseif and else are followed as separate paths. A return inside any other
	 * control structure, such as a loop, switch or try, counts as reaching a return.
	 *
	 * @param int $scopeOpener The scope opener.
	 * @param int $scopeCloser The scope closer.
	 *
	 * @return bool
	 */
	private function alwaysReturns( $scopeOpener, $scopeCloser ): bool {

		for ( $i = $scopeOpener + 1; $i < $scopeCloser; $i++ ) {
			if ( $this->tokens[ $i ]['code'] === T_RETURN ) {
				return true;
			}

			if ( isset( $this->tokens[ $i ]['scope_condition'], $this->tokens[ $i ]['scope_opener'], $this->tokens[ $i ]['scope_closer'] ) === false
				|| $this->tokens[ $i ]['scope_condition'] !== $i
			) {
				continue;
			}

			if ( $this->isNestedScope( $i ) ) {
				// A return in a nested function or class does not return from the callback.
				$i = $this->tokens[ $i ]['scope_closer'];
				continue;
			}

			if ( $this->tokens[ $i ]['code'] === T_IF ) {
				// An if chain only returns on every path when it has an else and every branch returns.
				$branches   = $this->getIfChainBranches( $i );
				$lastBranch = end( $branches );
				$returns    = $this->tokens[ $lastBranch ]['code'] === T_ELSE;
				foreach ( $branches as $branch ) {
					$returns = $returns && $this->alwaysReturns( $this->tokens[ $branch ]['scope_opener'], $this->tokens[ $branch ]['scope_closer'] );
				}

				if ( $returns ) {
					return true;
				}

				$i = $this->tokens[ $lastBranch ]['scope_closer'];
				continue;
			}

			if ( $this->alwaysReturns( $this->tokens[ $i ]['scope_opener'], $this->tokens[ $i ]['scope_closer'] ) ) {
				return true;
			}

			$i = $this->tokens[ $i ]['scope_closer'];
		}

		return false;
	}

	/**
	 * Get the if, elseif and else branches of an if chain.
	 *
	 * A branch without braces ends the chain, as it has no scope to follow.
	 *
	 * @param int $ifPtr The position of the if.
	 *
	 * @return array<int> The positions of the branches, in order.
	 */
	private function getIfChainBranches( $ifPtr ): array {

		$branches = [];
		$branch   = $ifPtr;
		while ( isset( $this->tokens[ $branch ]['scope_opener'], $this->tokens[ $branch ]['scope_closer'] ) ) {
			$branches[] = $branch;
			if ( $this->tokens[ $branch ]['code'] === T_ELSE ) {
				break;
			}

			// With the alternative syntax, a branch closes on the next elseif or else.
			$next = $this->tokens[ $branch ]['scope_closer'];
			if ( $this->tokens[ $next ]['code'] !== T_ELSEIF && $this->tokens[ $next ]['code'] !== T_ELSE ) {
				$next = $this->phpcsFile->findNext( Tokens::$emptyTokens, $next + 1, null, true );
			}

			if ( $next !== false
				&& $this->tokens[ $next ]['code'] === T_ELSE
				&& isset( $this->tokens[ $next ]['scope_opener'] ) === false
			) {
				// An `else if` is an else without braces, followed by an if.
				$next = $this->phpcsFile->findNext( Tokens::$emptyTokens, $next + 1, null, true );
				if ( $next === false || $this->tokens[ $next ]['code'] !== T_IF ) {
					break;
				}
			} elseif ( $next === false
				|| ( $this->tokens[ $next ]['code'] !== T_ELSEIF && $this->tokens[ $next ]['code'] !== T_ELSE )
			) {
				break;
			}

			$branch = $next;
		}

		return $branches;
	}

	/**
	 * Is the token a nested function or class, whose code does not belong to the callback?
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 *
	 * @return bool
	 */
	private function isNestedScope( $stackPtr ): bool {

		$code = $this->tokens[ $stackPtr ]['code'];

		return isset( $this->tokens[ $stackPtr ]['scope_closer'] )
			&& ( isset( Collections::functionDeclarationTokens()[ $code ] ) || isset( Tokens::$ooScopeTokens[ $code ] ) );
	}

	/**
	 * Check whether the function body contains an exit, die, or throw statement.
	 *
	 * @param int $scopeStart The scope opener of the function body.
	 * @param int $scopeEnd   The scope closer of the function body.
	 *
	 * @return bool
	 */
	private function hasTerminatingStatement( $scopeStart, $scopeEnd ): bool {

		for ( $i = $scopeStart + 1; $i < $scopeEnd; $i++ ) {
			if ( $this->isNestedScope( $i ) ) {
				// An exit or throw in a nested function or class does not end the callback.
				$i = $this->tokens[ $i ]['scope_closer'];
				continue;
			}

			if ( $this->tokens[ $i ]['code'] === T_EXIT || $this->tokens[ $i ]['code'] === T_THROW ) {
				return true;
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
	private function isReturningVoid( $stackPtr ): bool {

		$nextToReturnTokenPtr = $this->phpcsFile->findNext(
			Tokens::$emptyTokens,
			$stackPtr + 1,
			null,
			true
		);

		return $this->tokens[ $nextToReturnTokenPtr ]['code'] === T_SEMICOLON;
	}
}

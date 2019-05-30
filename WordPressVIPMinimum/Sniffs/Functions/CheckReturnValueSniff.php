<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Functions;

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
 */
class CheckReturnValueSniff extends Sniff {

	/**
	 * Pairs we are about to check.
	 *
	 * @var array
	 */
	public $catch = [
		'esc_url'          => [
			'get_term_link',
		],
		'wp_list_pluck'    => [
			'get_the_tags',
			'get_the_terms',
		],
		'foreach'          => [
			'get_post_meta',
			'get_term_meta',
			'get_the_terms',
			'get_the_tags',
		],
		'array_key_exists' => [
			'get_option',
		],
	];

	/**
	 * Tokens we are about to examine, which are not functions.
	 *
	 * @var array
	 */
	public $notFunctions = [
		'foreach' => T_FOREACH,
	];

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
	 * @param int $stackPtr The position in the stack where
	 *                        the token was found.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		$this->findDirectFunctionCalls( $stackPtr );
		$this->findNonCheckedVariables( $stackPtr );
	}

	/**
	 * Check whether the currently examined code is a function call.
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
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
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
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

	/**
	 * Find instances in which a function call is directly passed to another one w/o checking the return type
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 */
	public function findDirectFunctionCalls( $stackPtr ) {

		$functionName = $this->tokens[ $stackPtr ]['content'];

		if ( false === array_key_exists( $functionName, $this->catch ) ) {
			// Not a function we are looking for.
			return;
		}

		if ( false === $this->isFunctionCall( $stackPtr ) ) {
			// Not a function call.
			return;
		}

		// Find the next non-empty token.
		$openBracket = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true );

		// Find the closing bracket.
		$closeBracket = $this->tokens[ $openBracket ]['parenthesis_closer'];

		$startNext = $openBracket + 1;
		$next      = $this->phpcsFile->findNext( Tokens::$functionNameTokens, $startNext, $closeBracket, false, null, true );
		while ( $next ) {
			if ( true === in_array( $this->tokens[ $next ]['content'], $this->catch[ $functionName ], true ) ) {
				$message = "`%s`'s return type must be checked before calling `%s` using that value.";
				$data    = [ $this->tokens[ $next ]['content'], $functionName ];
				$this->phpcsFile->addError( $message, $next, 'DirectFunctionCall', $data );
			}
			$next = $this->phpcsFile->findNext( Tokens::$functionNameTokens, $next + 1, $closeBracket, false, null, true );
		}
	}

	/**
	 * Deals with situations in which the variable is being used later in the code along with a function which is known for causing issues.
	 *
	 * This only catches situations in which the variable is not being used with some other function before it's interacting with function we look for.
	 * That's currently necessary in order to prevent false positives.
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function findNonCheckedVariables( $stackPtr ) {

		$functionName = $this->tokens[ $stackPtr ]['content'];

		$isFunctionWeLookFor = false;

		$callees = [];

		foreach ( $this->catch as $callee => $checkReturnArray ) {
			if ( true === in_array( $functionName, $checkReturnArray, true ) ) {
				$isFunctionWeLookFor = true;
				$callees[]           = $callee;
			}
		}

		if ( false === $isFunctionWeLookFor ) {
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

		if ( true === in_array( $functionName, [ 'get_post_meta', 'get_term_meta' ], true ) ) {
			// Since the get_post_meta and get_term_meta always returns an array if $single is set to `true` we need to check for the value of it's third param before proceeding.
			$params       = [];
			$paramNo      = 1;
			$prevCommaPos = $openBracket + 1;

			for ( $i = $openBracket + 1; $i <= $closeBracket; $i++ ) {

				if ( T_OPEN_PARENTHESIS === $this->tokens[ $i ]['code'] ) {
					$i = $this->tokens[ $i ]['parenthesis_closer'];
				}

				if ( T_COMMA === $this->tokens[ $i ]['code'] ) {
					$params[ $paramNo++ ] = trim( array_reduce( array_slice( $this->tokens, $prevCommaPos, $i - $prevCommaPos ), [ $this, 'reduce_array' ] ) );
					$prevCommaPos         = $i + 1;
				}

				if ( $i === $closeBracket ) {
					$params[ $paramNo ] = trim( array_reduce( array_slice( $this->tokens, $prevCommaPos, $i - $prevCommaPos ), [ $this, 'reduce_array' ] ) );
					break;
				}
			}

			if ( false === array_key_exists( 3, $params ) || 'false' === $params[3] ) {
				// Third param of get_post_meta is not set (default to false) or is set to false.
				// Means the function returns an array. We are good then.
				return;
			}
		}

		$nextVariableOccurrence = $this->phpcsFile->findNext( T_VARIABLE, $closeBracket + 1, null, false, $variableName );

		// Find previous non-empty token, which is not an open parenthesis, comma nor variable.
		$search   = Tokens::$emptyTokens;
		$search[] = T_OPEN_PARENTHESIS;
		// This allows us to check for variables which are passed as second parameter of a function e.g.: array_key_exists.
		$search[] = T_COMMA;
		$search[] = T_VARIABLE;
		$search[] = T_CONSTANT_ENCAPSED_STRING;

		$nextFunctionCallWithVariable = $this->phpcsFile->findPrevious( $search, $nextVariableOccurrence - 1, null, true );

		foreach ( $callees as $callee ) {
			$notFunctionsCallee = array_key_exists( $callee, $this->notFunctions ) ? (array) $this->notFunctions[ $callee ] : [];
			// Check whether the found token is one of the function calls (or foreach call) we are interested in.
			if ( true === in_array( $this->tokens[ $nextFunctionCallWithVariable ]['code'], array_merge( Tokens::$functionNameTokens, $notFunctionsCallee ), true )
				&& $this->tokens[ $nextFunctionCallWithVariable ]['content'] === $callee
			) {
				$this->addNonCheckedVariableError( $nextFunctionCallWithVariable, $variableName, $callee );
				return;
			}

			$search = array_merge( Tokens::$emptyTokens, [ T_EQUAL ] );
			$next   = $this->phpcsFile->findNext( $search, $nextVariableOccurrence + 1, null, true );
			if ( true === in_array( $this->tokens[ $next ]['code'], Tokens::$functionNameTokens, true )
				&& $this->tokens[ $next ]['content'] === $callee
			) {
				$this->addNonCheckedVariableError( $next, $variableName, $callee );
				return;
			}
		}
	}

	/**
	 * Function used as as callback for the array_reduce call.
	 *
	 * @param string $carry The final string.
	 * @param array  $item Processed item.
	 *
	 * @return string
	 */
	public function reduce_array( $carry, $item ) {
		return $carry . $item['content'];
	}

	/**
	 * Consolidated violation.
	 *
	 * @param int    $stackPtr     The position in the stack where the token was found.
	 * @param string $variableName Variable name.
	 * @param string $callee       Function name.
	 */
	private function addNonCheckedVariableError( $stackPtr, $variableName, $callee ) {
		$message = 'Type of `%s` must be checked before calling `%s()` using that variable.';
		$data    = [ $variableName, $callee ];
		$this->phpcsFile->addError( $message, $stackPtr, 'NonCheckedVariable', $data );
	}

}

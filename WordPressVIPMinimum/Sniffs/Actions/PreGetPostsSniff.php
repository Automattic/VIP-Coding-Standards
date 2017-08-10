<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

/**
 * This sniff validates a propper usage of pre_get_posts action callback
 *
 * It looks for cases when the WP_Query object is being modified without checking for WP_Query::is_main_query().
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class WordPressVIPminimum_Sniffs_Actions_PreGetPostsSniff implements PHP_CodeSniffer_Sniff {

	/**
	 * The tokens of the phpcsFile.
	 *
	 * @var array
	 */
	private $_tokens;

	/**
	 * The phpcsFile.
	 *
	 * @var phpcsFile
	 */
	private $_phpcsFile;

	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * @return array(int)
	 */
	public function register() {
		return PHP_CodeSniffer_Tokens::$functionNameTokens;

	}//end register()


	/**
	 * Processes the tokens that this sniff is interested in.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
	 * @param int                  $stackPtr  The position in the stack where
	 *                                        the token was found.
	 *
	 * @return void
	 */
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {

		$this->_tokens = $phpcsFile->getTokens();

		$this->_phpcsFile = $phpcsFile;

		$functionName = $this->_tokens[ $stackPtr ]['content'];

		if ( 'add_action' !== $functionName ) {
			// We are interested in add_action calls only.
			return;
		}

		$actionNamePtr = $this->_phpcsFile->findNext(
			array_merge( PHP_CodeSniffer_Tokens::$emptyTokens, array( T_OPEN_PARENTHESIS ) ), // types.
			$stackPtr + 1, // start.
			null, // end.
			true, // exclude.
			null, // value.
			true // local.
		);

		if ( ! $actionNamePtr ) {
			// Something is wrong.
			return;
		}

		if ( 'pre_get_posts' !== substr( $this->_tokens[ $actionNamePtr ]['content'], 1, -1 ) ) {
			// This is not setting a callback for pre_gest_posts action.
			return;
		}

		$callbackPtr = $this->_phpcsFile->findNext(
			array_merge( PHP_CodeSniffer_Tokens::$emptyTokens, array( T_COMMA ) ), // types.
			$actionNamePtr + 1, // start.
			null, // end.
			true, // exclude.
			null, // value.
			true // local.
		);

		if ( ! $callbackPtr ) {
			// Something is wrong.
			return;
		}

		if ( 'PHPCS_T_CLOSURE' === $this->_tokens[ $callbackPtr ]['code'] ) {
			$this->processClosure( $callbackPtr );
		} else if ( 'T_ARRAY' === $this->_tokens[ $callbackPtr ]['type'] ) {
			$this->processArray( $callbackPtr );
		} else if ( true === in_array( $this->_tokens[ $callbackPtr ]['code'], PHP_CodeSniffer_Tokens::$stringTokens ) ) {
			$this->processString( $callbackPtr );
		}

	}

	/**
	 * Process array.
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 */
	private function processArray( $stackPtr ) {

		$previous = $this->_phpcsFile->findPrevious(
			PHP_CodeSniffer_Tokens::$emptyTokens, // types
			$this->_tokens[ $stackPtr ]['parenthesis_closer'] - 1, // start.
			null, // end.
			true, // exclude.
			null, // value.
			false // local.
		);

		$this->processString( $previous );

	}

	/**
	 * Process string.
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 */
	private function processString( $stackPtr ) {

		$callbackFunctionName = substr( $this->_tokens[ $stackPtr ]['content'], 1, -1 );

		$callbackFunctionPtr = $this->_phpcsFile->findNext(
			PHP_CodeSniffer_Tokens::$functionNameTokens, // types.
			0, // start.
			null, // end.
			false, // exclude.
			$callbackFunctionName, // value.
			false // local.
		);

		if ( ! $callbackFunctionPtr ) {
			// We were not able to find the function callback in the file.
			return;
		}

		$this->processFunction( $callbackFunctionPtr );

	}

	/**
	 * Process function.
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 */
	private function processFunction( $stackPtr ) {

		$wpQueryObjectNamePtr = $this->_phpcsFile->findNext(
			array( T_VARIABLE ), // types.
			$stackPtr + 1, // start.
			null, // end.
			false, // exclude.
			null, // value.
			true // local.
		);

		if ( ! $wpQueryObjectNamePtr ) {
			// Something is wrong.
			return;
		}

		$wpQueryObjectVariableName = $this->_tokens[ $wpQueryObjectNamePtr ]['content'];

		$functionDefinitionPtr = $this->_phpcsFile->findPrevious(
			array( T_FUNCTION ), // types.
			$wpQueryObjectNamePtr - 1, // start.
			null, // end.
			false, // exlcude.
			null, // value.
			false // local.
		);

		if ( ! $functionDefinitionPtr ) {
			// Something is wrong.
			return;
		}

		$this->processFunctionBody( $functionDefinitionPtr, $wpQueryObjectVariableName );

	}

	/**
	 * Process closure.
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 */
	private function processClosure( $stackPtr ) {

		$wpQueryObjectNamePtr = $this->_phpcsFile->findNext(
			array( T_VARIABLE ), // types.
			$stackPtr + 1, // start.
			null, // end.
			false, // exclude.
			null, // value.
			true // local.
		);

		if ( ! $wpQueryObjectNamePtr ) {
			// Something is wrong.
			return;
		}

		$this->processFunctionBody( $stackPtr, $this->_tokens[ $wpQueryObjectNamePtr ]['content'] );

	}

	/**
	 * Process function's body
	 *
	 * @param int    $stackPtr The position in the stack where the token was found.
	 * @param string $variableName Variable name.
	 */
	private function processFunctionBody( $stackPtr, $variableName ) {

		$functionBodyScopeStart = $this->_tokens[ $stackPtr ]['scope_opener'];
		$functionBodyScopeEnd = $this->_tokens[ $stackPtr ]['scope_closer'];

		$findStart = $functionBodyScopeStart + 1;

		while ( $wpQueryVarUsed = $this->_phpcsFile->findNext(
			array( T_VARIABLE ), // types.
			$findStart, // start.
			$functionBodyScopeEnd, // end.
			false, // exclude.
			$variableName, // value.
			false // local.
		) ) {
			if ( $this->isPartOfIfConditional( $wpQueryVarUsed ) ) {
				if ( $this->isEarlyMainQueryCheck( $wpQueryVarUsed ) ) {
					return;
				}
			} else if ( $this->isInsideIfConditonal( $wpQueryVarUsed ) ) {
				if ( ! $this->isParentConditionalCheckingMainQuery( $wpQueryVarUsed ) ) {
					$this->_phpcsFile->addWarning( 'Main WP_Query is being modified without $query->is_main_query() check. Needs manual inspection.', $wpQueryVarUsed );
				}
			} else if ( $this->isWPQueryMethodCall( $wpQueryVarUsed, 'set' ) ) {
				$this->_phpcsFile->addWarning( 'Main WP_Query is being modified without $query->is_main_query() check. Needs manual inspection.', $wpQueryVarUsed );
			}
			$findStart = $wpQueryVarUsed + 1;
		}
	}

	/**
	 * Is parent conditional checking is_main_query?
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 *
	 * @return bool
	 */
	private function isParentConditionalCheckingMainQuery( $stackPtr ) {

		if ( false === array_key_exists( 'conditions', $this->_tokens[ $stackPtr ] ) ||
			 false === is_array( $this->_tokens[ $stackPtr ]['conditions'] ) ||
			 true === empty( $this->_tokens[ $stackPtr ]['conditions'] )
		) {
			return false;
		}

		$conditionStackPtrs = array_keys( $this->_tokens[ $stackPtr ]['conditions'] );
		$lastConditionStackPtr = array_pop( $conditionStackPtrs );

		while ( T_IF === $this->_tokens[ $stackPtr ]['conditions'][ $lastConditionStackPtr ] ) {

			$searchStart = $lastConditionStackPtr + 1;
			while ( $next = $this->_phpcsFile->findNext(
				array( T_VARIABLE ), // types.
				$searchStart, // start.
				null, // end.
				false, // exclude.
				$this->_tokens[ $stackPtr ]['content'], // value.
				true // local.
			) ) {
				if ( true === $this->isWPQueryMethodCall( $next, 'is_main_query' ) ) {
					return true;
				}
				$searchStart = $next + 1;
			}

			$lastConditionStackPtr = array_pop( $conditionStackPtrs );
		}

		return false;
	}


	/**
	 * Is the current code an early main query check?
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 *
	 * @return bool
	 */
	private function isEarlyMainQueryCheck( $stackPtr ) {

		if ( ! $this->isWPQueryMethodCall( $stackPtr, 'is_main_query' ) ) {
			return false;
		}

		if ( false === array_key_exists( 'nested_parenthesis', $this->_tokens[ $stackPtr ] ) ||
			 true === empty( $this->_tokens[ $stackPtr ]['nested_parenthesis'] )
		) {
			return false;
		}

		$nestedParenthesisEnd = array_shift( $this->_tokens[ $stackPtr ]['nested_parenthesis'] );
		if ( true === in_array( 'PHPCS_T_CLOSURE', $this->_tokens[ $stackPtr ]['conditions'] ) ) {
			$nestedParenthesisEnd = array_shift( $this->_tokens[ $stackPtr ]['nested_parenthesis'] );
		}

		$next = $this->_phpcsFile->findNext(
			array( T_RETURN ), // types.
			$this->_tokens[ $this->_tokens[ $nestedParenthesisEnd ]['parenthesis_owner'] ]['scope_opener'], // start.
			$this->_tokens[ $this->_tokens[ $nestedParenthesisEnd ]['parenthesis_owner'] ]['scope_closer'], // end.
			false, // exclude.
			'return', // value.
			true // local.
		);

		if ( $next ) {
			return true;
		}

		return false;
	}

	/**
	 * Is the current code a WP_Query call?
	 *
	 * @param int  $stackPtr The position in the stack where the token was found.
	 * @param null $method Method.
	 *
	 * @return bool
	 */
	private function isWPQueryMethodCall( $stackPtr, $method = null ) {
		$next = $this->_phpcsFile->findNext(
			PHP_CodeSniffer_Tokens::$emptyTokens, // types.
			$stackPtr + 1, // start.
			null, // end.
			true, // exclude.
			null, // value.
			true // local.
		);

		if ( ! $next || 'T_OBJECT_OPERATOR' !== $this->_tokens[ $next ]['type'] ) {
			return false;
		}

		if ( null === $method ) {
			return true;
		}

		$next = $this->_phpcsFile->findNext(
			PHP_CodeSniffer_Tokens::$emptyTokens, // types.
			$next + 1, // start.
			null, // end.
			true, // exclude.
			null, // value.
			true // local.
		);

		if ( $next &&
			 true === in_array( $this->_tokens[ $next ]['code'], PHP_CodeSniffer_Tokens::$functionNameTokens, true ) &&
			 $method === $this->_tokens[ $next ]['content']
		) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Is the current token a part of a conditional?
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 *
	 * @return bool
	 */
	private function isPartofIfConditional( $stackPtr ) {

		if ( true === array_key_exists( 'nested_parenthesis', $this->_tokens[ $stackPtr ] ) &&
			 true === is_array( $this->_tokens[ $stackPtr ]['nested_parenthesis'] ) &&
			 false === empty( $this->_tokens[ $stackPtr ]['nested_parenthesis'] )
		) {
			$previousLocalIf = $this->_phpcsFile->findPrevious(
				array( T_IF ), // types.
				$stackPtr - 1, // start.
				null, // end.
				false, // exclude.
				null, // value.
				true // local.
			);
			if ( false !== $previousLocalIf &&
				 $this->_tokens[ $previousLocalIf ]['parenthesis_opener'] < $stackPtr &&
				 $this->_tokens[ $previousLocalIf ]['parenthesis_closer'] > $stackPtr
			) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Is the current token inside a conditional?
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 *
	 * @return bool
	 */
	private function isInsideIfConditonal( $stackPtr ) {

		if ( true === array_key_exists( 'conditions', $this->_tokens[ $stackPtr ] ) &&
			 true === is_array( $this->_tokens[ $stackPtr ]['conditions'] ) &&
			 false === empty( $this->_tokens[ $stackPtr ]['conditions'] )
		) {
			$conditionStackPtrs = array_keys( $this->_tokens[ $stackPtr ]['conditions'] );
			$lastConditionStackPtr = array_pop( $conditionStackPtrs );
			return T_IF === $this->_tokens[ $stackPtr ]['conditions'][ $lastConditionStackPtr ];
		}
		return false;
	}
}

<?php
/**
 * This sniff enforces checking the return value of a function before passing it to anoher one.
 *
 * An example of a not checking return value is:
 *
 * <code>
 * echo esc_url( wpcom_vip_get_term_link( $term ) );
 * </code>
 */
class WordPressVIPminimum_Sniffs_Functions_CheckReturnValueSniff implements PHP_CodeSniffer_Sniff {

	private $_tokens = array();

	public $catch = array(
		'esc_url' => array(
			'wpcom_vip_get_term_link',
			'get_term_link',
		),
		'wp_list_pluck' => array(
			'get_the_tags',
			'get_the_terms',
		),
		'foreach' => array(
			'get_post_meta',
			'get_term_meta',
			'get_the_terms',
			'get_the_tags',
		),
		'array_key_exists' => array(
			'get_option',
		)
	);

	public $notFunctions = array(
		'foreach' => T_FOREACH,
	);

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
	 * @param int				  $stackPtr  The position in the stack where
	 *										the token was found.
	 *
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {

		$this->_tokens = $phpcsFile->getTokens();
		$this->_phpcsFile = $phpcsFile;

		$this->findDirectFunctionCalls( $stackPtr );
		$this->findNonCheckedVariables( $stackPtr );
	
	}//end Process()

	private function isFunctionCall( $stackPtr ) {

		$tokens = $this->_tokens;
		$phpcsFile = $this->_phpcsFile;

		if ( false === in_array( $tokens[$stackPtr]['code'], PHP_CodeSniffer_Tokens::$functionNameTokens ) ) {
			return false;
		}

		// Find the next non-empty token.
		$openBracket = $phpcsFile->findNext( PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true );

		if ( $tokens[$openBracket]['code'] !== T_OPEN_PARENTHESIS ) {
			// Not a function call.
			return false;
		}

		// Find the previous non-empty token.
		$search   = PHP_CodeSniffer_Tokens::$emptyTokens;
		$search[] = T_BITWISE_AND;
		$previous = $phpcsFile->findPrevious( $search, ($stackPtr - 1), null, true );
		if ( $tokens[$previous]['code'] === T_FUNCTION ) {
			// It's a function definition, not a function call.
			return false;
		}

		return true;
	}

	private function isVariableAssignment( $stackPtr ) {

		$tokens = $this->_tokens;
		$phpcsFile = $this->_phpcsFile;

		// Find the previous non-empty token.
		$search   = PHP_CodeSniffer_Tokens::$emptyTokens;
		$search[] = T_BITWISE_AND;
		$previous = $phpcsFile->findPrevious($search, ($stackPtr - 1), null, true);

		if ( $tokens[$previous]['code'] !== T_EQUAL ) {
			// It's not a variable assignment.
			return false;
		}

		$previous = $phpcsFile->findPrevious($search, ($previous - 1), null, true);

		if ( $tokens[$previous]['code'] !== T_VARIABLE ) {
			// It's not a variable assignment.
			return false;
		}

		return $previous;

	}

	/**
	 * Find instances in which a function call is directly passed to another one w/o checking the return type
	 */
	public function findDirectFunctionCalls( $stackPtr ) {
		$tokens = $this->_tokens;
		$phpcsFile = $this->_phpcsFile;

		$functionName = $tokens[$stackPtr]['content'];

		if ( false === array_key_exists( $functionName, $this->catch ) ) {
			//Not a function we are looking for.
			return;
		}

		if ( false === $this->isFunctionCall( $stackPtr ) ) {
			// Not a function call.
			return;
		}

		// Find the next non-empty token.
		$openBracket = $phpcsFile->findNext( PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true );

		// Find the closing bracket
		$closeBracket = $tokens[$openBracket]['parenthesis_closer'];

		$startNext = $openBracket + 1;
		while ( $next = $phpcsFile->findNext( PHP_CodeSniffer_Tokens::$functionNameTokens, $startNext, $closeBracket, false, null, true ) ) {
			if ( true === in_array( $tokens[$next]['content'], $this->catch[$functionName], true ) ) {
				$phpcsFile->addError( sprintf( "%s's return type must be checked before calling %s using that value", $tokens[$next]['content'], $functionName ), $next );
			}
			$startNext = $next + 1;
		}

	}//end findDirectFunctionCalls()

	/**
	 * Deals with situations in which the variable is being used later in the code along with a function which is known for causing issues.
	 *
	 * This only catches situations in which the variable is not being used with some other function before it's interacting with function we look for.
	 * That's currently necessary in order to prevent false positives.
	 */
	public function findNonCheckedVariables( $stackPtr ) {

		$tokens = $this->_tokens;
		$phpcsFile = $this->_phpcsFile;

		$functionName = $tokens[$stackPtr]['content'];

		$isFunctionWeLookFor = false;

		$callees = array();

		foreach( $this->catch as $callee => $checkReturnArray ) {
			if ( true === in_array( $functionName, $checkReturnArray ) ) {
				$isFunctionWeLookFor = true;
				$callees[] = $callee;
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

		$variableToken = $tokens[$variablePos];
		$variableName = $variableToken['content'];

		// Find the next non-empty token.
		$openBracket = $phpcsFile->findNext( PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true );

		// Find the closing bracket
		$closeBracket = $tokens[$openBracket]['parenthesis_closer'];

		if ( true === in_array( $functionName, array( 'get_post_meta', 'get_term_meta' ), true ) ) {
			// Since the get_post_meta and get_term_meta always returns an array if $single is set to `true` we need to check for the value of it's third param before proceeding

			$params = array();
			$paramNo = 1;
			$prevCommaPos = $openBracket + 1;

			for( $i = $openBracket + 1; $i <= $closeBracket; $i++ ) {

				if ( T_OPEN_PARENTHESIS === $tokens[$i]['code'] ) {
					$i = $tokens[$i]['parenthesis_closer'];
				}

				if ( T_COMMA === $tokens[$i]['code'] ) {
					$params[$paramNo++] = trim( array_reduce( array_slice( $tokens, $prevCommaPos, $i - $prevCommaPos ), array( $this, 'reduce_array' )  ) );
					$prevCommaPos = $i + 1;
				}

				if ( $i === $closeBracket ) {
					$params[$paramNo] = trim( array_reduce( array_slice( $tokens, $prevCommaPos, $i - $prevCommaPos ), array( $this, 'reduce_array' ) ) );
					break;
				}
			
			}
			
			if ( false === array_key_exists( 3, $params ) || 'false' === $params[3] ) {
				// Third param of get_post_meta is not set (default to false) or is set to false.
				// Means the function returns an array. We are good then.
				return;
			}
		}
		
		$nextVariableOccurrence = $phpcsFile->findNext( T_VARIABLE, ($closeBracket + 1), null, false, $variableName, false );

		// Find previous non-empty token, which is not an open parenthesis, comma nor variable.
		$search = PHP_CodeSniffer_Tokens::$emptyTokens;
		$search[] = T_OPEN_PARENTHESIS;
		// This allows us to check for variables which are passed as second paramt of a function. Eg.: array_key_exists.
		$search[] = T_COMMA;
		$search[] = T_VARIABLE; 
		$search[] = T_CONSTANT_ENCAPSED_STRING;
		$nextFunctionCallWithVariable = $phpcsFile->findPrevious( $search, ($nextVariableOccurrence - 1), null, true);

		foreach( $callees as $callee ) {
			$notFunctionsCallee = array_key_exists( $callee, $this->notFunctions ) ? (array) $this->notFunctions[$callee] : array();
			// Check whether the found token is one of the function calls (or foreach call) we are interested in.
			if ( true === in_array( $tokens[$nextFunctionCallWithVariable]['code'], array_merge( PHP_CodeSniffer_Tokens::$functionNameTokens, $notFunctionsCallee ), true )
				 && $tokens[$nextFunctionCallWithVariable]['content'] === $callee
			) {
				$phpcsFile->addError( sprintf( "Type of %s must be checked before calling %s using that variable", $variableName, $callee ), $nextFunctionCallWithVariable );
				return;
			
			}

			$search = array_merge( PHP_CodeSniffer_Tokens::$emptyTokens, array( T_EQUAL ) );
        	$next = $phpcsFile->findNext( $search, $nextVariableOccurrence + 1, null, true, null, false );
        	if ( true === in_array( $tokens[$next]['code'], PHP_CodeSniffer_Tokens::$functionNameTokens, true )
            	 && $tokens[$next]['content'] === $callee
        	) {
            	$phpcsFile->addError( sprintf( "Type of %s must be checked before calling %s using that variable", $variableName, $callee ), $next );
				return;
        	}
		}
	}

	public function reduce_array( $carry, $item ) {
		return $carry .= $item['content'];
	}

}//end class



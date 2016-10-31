<?php
/**
 * This sniff enforces checking the return value of a function before passing it to anoher one.
 *
 * An example of a not checking return value is:
 *
 * <code>
 * echo esc_url( wpcom_vip_get_term_link( $term ) );
 * </code>
 * 
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author	Your Name <you@domain.net>
 * @version   1.0.0
 * @link	  http://pear.php.net/package/PHP_CodeSniffer
 */
class WordPressVIPminimum_Sniffs_Functions_CheckReturnValueSniff implements PHP_CodeSniffer_Sniff {

	public $catch = array(
		'esc_url' => array(
			'wpcom_vip_get_term_link',
			'get_term_link',
		),
		'wp_list_pluck' => array(
			'get_the_tags',
			'get_the_terms',
		),
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
	
		$this->findDirectFunctionCalls( $phpcsFile, $stackPtr );
		$this->findNonCheckedVariables( $phpcsFile, $stackPtr );
	
	}//end Process()

	public function findDirectFunctionCalls( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$functionName = $tokens[$stackPtr]['content'];

		if ( false === array_key_exists( $functionName, $this->catch ) ) {
			//Not a function we are looking for.
			return;
		}

		// Find the next non-empty token.
        $openBracket = $phpcsFile->findNext( PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true );

		if ( $tokens[$openBracket]['code'] !== T_OPEN_PARENTHESIS ) {
			// Not a function call.
			return;
		}

		// Find the previous non-empty token.
		$search   = PHP_CodeSniffer_Tokens::$emptyTokens;
		$search[] = T_BITWISE_AND;
		$previous = $phpcsFile->findPrevious( $search, ($stackPtr - 1), null, true );
		if ( $tokens[$previous]['code'] === T_FUNCTION ) {
			// It's a function definition, not a function call.
			return;
		}

		$closeBracket = $tokens[$openBracket]['parenthesis_closer'];

		$startNext = $openBracket + 1;
		while ( $next = $phpcsFile->findNext( PHP_CodeSniffer_Tokens::$functionNameTokens, $startNext, $closeBracket, false, null, true ) ) {
			if ( true === in_array( $tokens[$next]['content'], $this->catch[$functionName], true ) ) {
				$phpcsFile->addError( sprintf( "%s's return type must be checked before calling %s using that value", $tokens[$next]['content'], $functionName ), $next );
			}
			$startNext = $next + 1;
		}

	}//end findDirectFunctionCalls()

	public function findNonCheckedVariables( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		$functionName = $tokens[$stackPtr]['content'];

		$isFuncitonWeLookFor = false;
		foreach( $this->catch as $callee => $checkReturnArray ) {
			if ( true === in_array( $functionName, $checkReturnArray ) ) {
				$isFuncitonWeLookFor = true;
				break;
			}
		}

		if ( false === $isFuncitonWeLookFor ) {
			//Not a function we are looking for.
			return;
		}

		// Find the next non-empty token.
		$openBracket = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);
		
		if ( $tokens[$openBracket]['code'] !== T_OPEN_PARENTHESIS ) {
			// Not a function call.
			return;
		}

		if ( isset( $tokens[$openBracket]['parenthesis_closer'] ) === false ) {
			// Not a function call.
			return;
		}

		// Find the previous non-empty token.
		$search   = PHP_CodeSniffer_Tokens::$emptyTokens;
		$search[] = T_BITWISE_AND;
		$previous = $phpcsFile->findPrevious($search, ($stackPtr - 1), null, true);
		if ( $tokens[$previous]['code'] === T_FUNCTION ) {
			// It's a function definition, not a function call.
			return;
		}
		if ( $tokens[$previous]['code'] !== T_EQUAL ) {
			// It's not a variable assignment.
			return;
		}
		$previous = $phpcsFile->findPrevious($search, ($previous - 1), null, true);
		if ( $tokens[$previous]['code'] !== T_VARIABLE ) {
			// It's not a variable assignment.
			return;
		}

		$variableName = $tokens[$previous]['content'];

		$closeBracket = $tokens[$openBracket]['parenthesis_closer'];

		$nextVariableOccurence = $phpcsFile->findNext( T_VARIABLE, ($closeBracket + 1), null, false, $variableName, false );

		$search = PHP_CodeSniffer_Tokens::$emptyTokens;
		$search[] = T_OPEN_PARENTHESIS;
		$previous = $phpcsFile->findPrevious( $search, ($nextVariableOccurence - 1), null, true);
		if ( true === in_array( $tokens[$previous]['code'], PHP_CodeSniffer_Tokens::$functionNameTokens, true )
			 && $tokens[$previous]['content'] === $callee
		) {
			$phpcsFile->addError( sprintf( "Type of %s must be checked before calling %s using that variable", $variableName, $callee ), $previous );		
		}

		$search = PHP_CodeSniffer_Tokens::$emptyTokens;
		$search[] = T_EQUAL;
		$next = $phpcsFile->findNext( $search, $nextVariableOccurence + 1, null, true, null, false );
		if ( true === in_array( $tokens[$next]['code'], PHP_CodeSniffer_Tokens::$functionNameTokens, true )
			 && $tokens[$next]['content'] === $callee
		) {
			$phpcsFile->addError( sprintf( "Type of %s must be checked before calling %s using that variable", $variableName, $callee ), $next );
		}

	}

}//end class


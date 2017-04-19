<?php
/**
 * WordPress-VIP-Minimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

/**
 * Restricts usage of some constants.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class WordPressVIPMinimum_Sniffs_Constants_ConstantRestrictionsSniff implements PHP_CodeSniffer_Sniff {

	/**
	 * List of restricted constant names.
	 *
	 * @var array
	 */
	public $restrictedConstantNames = array(
		'A8C_PROXIED_REQUEST',
	);

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(
			T_CONSTANT_ENCAPSED_STRING,
			T_STRING,
		);
	}//end register()

	/**
	 * Process this test when one of its tokens is encoutnered
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int				   $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		if ( T_STRING === $tokens[ $stackPtr ]['code'] ) {
			$constantName = $tokens[ $stackPtr ]['content'];
		} else {
			$constantName = trim( $tokens[ $stackPtr ]['content'], "\"'" );
		}

		if ( false === in_array( $constantName, $this->restrictedConstantNames, true ) ) {
			// Not the constant we are looking for.
			return;
		}

		if ( T_STRING === $tokens[ $stackPtr ]['code'] ) {
			$phpcsFile->addWarning( sprintf( 'Code is touching the %s constant. Make sure it\'s used appropriately.', $constantName ), $stackPtr );
			return;
		}

		// Find the previous non-empty token.
		$openBracket = $phpcsFile->findPrevious( PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true, null, true );

		if ( T_OPEN_PARENTHESIS !== $tokens[ $openBracket ]['code'] ) {
			// Not a function call.
			return;
		}

		if ( false === isset( $tokens[ $openBracket ]['parenthesis_closer'] ) ) {
			// Not a function call.
			return;
		}

		// Find the previous non-empty token.
		$search   = PHP_CodeSniffer_Tokens::$emptyTokens;
		$search[] = T_BITWISE_AND;
		$previous = $phpcsFile->findPrevious( $search, ($openBracket - 1), null, true );
		if ( T_FUNCTION === $tokens[ $previous ]['code'] ) {
			// It's a function definition, not a function call.
			return;
		}

		if ( true === in_array( $tokens[ $previous ]['code'], PHP_CodeSniffer_Tokens::$functionNameTokens, true ) ) {
			if ( 'define' === $tokens[ $previous ]['content'] ) {
				$phpcsFile->addError( sprintf( 'The definition of %s constant is prohibited. Please use a different name.', $constantName ), $previous );
			} else {
				$phpcsFile->addWarning( sprintf( 'Code is touching the %s constant. Make sure it\'s used appropriately.', $constantName ), $previous );
			}
		}
	}

} // End class.

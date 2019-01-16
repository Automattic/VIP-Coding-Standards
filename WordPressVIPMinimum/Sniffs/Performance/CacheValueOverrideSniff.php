<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Performance;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
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
class CacheValueOverrideSniff implements Sniff {

	/**
	 * Tokens of the file.
	 *
	 * @var array
	 */
	private $_tokens = [];

	/**
	 * The PHP_CodeSniffer file where the token was found.
	 *
	 * @var File
	 */
	private $_phpcsFile;

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
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the stack where the token was found.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$this->_tokens    = $phpcsFile->getTokens();
		$tokens           = $phpcsFile->getTokens();
		$this->_phpcsFile = $phpcsFile;

		$functionName = $tokens[ $stackPtr ]['content'];

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

		$variableToken = $tokens[ $variablePos ];
		$variableName  = $variableToken['content'];

		// Find the next non-empty token.
		$openBracket = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );

		// Find the closing bracket.
		$closeBracket = $tokens[ $openBracket ]['parenthesis_closer'];

		$nextVariableOccurrence = $phpcsFile->findNext( T_VARIABLE, ( $closeBracket + 1 ), null, false, $variableName, false );

		$rightAfterNextVariableOccurence = $phpcsFile->findNext( Tokens::$emptyTokens, ( $nextVariableOccurrence + 1 ), null, true, null, true );

		if ( T_EQUAL !== $tokens[ $rightAfterNextVariableOccurence ]['code'] ) {
			// Not a value override.
			return;
		}

		$valueAfterEqualSign = $phpcsFile->findNext( Tokens::$emptyTokens, ( $rightAfterNextVariableOccurence + 1 ), null, true, null, true );

		if ( T_FALSE === $tokens[ $valueAfterEqualSign ]['code'] ) {
			$message = 'Obtained cached value in `%s` is being overridden. Disabling caching?';
			$data    = [ $variableName ];
			$phpcsFile->addError( $message, $nextVariableOccurrence, 'CacheValueOverride', $data );
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

		$tokens    = $this->_tokens;
		$phpcsFile = $this->_phpcsFile;

		if ( false === in_array( $tokens[ $stackPtr ]['code'], Tokens::$functionNameTokens, true ) ) {
			return false;
		}

		// Find the next non-empty token.
		$openBracket = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );

		if ( T_OPEN_PARENTHESIS !== $tokens[ $openBracket ]['code'] ) {
			// Not a function call.
			return false;
		}

		// Find the previous non-empty token.
		$search   = Tokens::$emptyTokens;
		$search[] = T_BITWISE_AND;
		$previous = $phpcsFile->findPrevious( $search, ( $stackPtr - 1 ), null, true );
		if ( T_FUNCTION === $tokens[ $previous ]['code'] ) {
			// It's a function definition, not a function call.
			return false;
		}

		return true;
	}

	/**
	 * Check whether the examined code is a variable assignment.
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return bool
	 */
	private function isVariableAssignment( $stackPtr ) {

		$tokens    = $this->_tokens;
		$phpcsFile = $this->_phpcsFile;

		// Find the previous non-empty token.
		$search   = Tokens::$emptyTokens;
		$search[] = T_BITWISE_AND;
		$previous = $phpcsFile->findPrevious( $search, ( $stackPtr - 1 ), null, true );

		if ( T_EQUAL !== $tokens[ $previous ]['code'] ) {
			// It's not a variable assignment.
			return false;
		}

		$previous = $phpcsFile->findPrevious( $search, ( $previous - 1 ), null, true );

		if ( T_VARIABLE !== $tokens[ $previous ]['code'] ) {
			// It's not a variable assignment.
			return false;
		}

		return $previous;
	}

}

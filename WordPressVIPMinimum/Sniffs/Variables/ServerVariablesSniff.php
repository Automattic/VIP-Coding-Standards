<?php
/**
 * WordPress-VIP-Minimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\Variables;

use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * Restricts usage of some server variables.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class ServerVariablesSniff implements \PHP_CodeSniffer_Sniff {

	/**
	 * List of restricted constant names.
	 *
	 * @var array
	 */
	public $restrictedVariables = array(
		'PHP_AUTH_PW',
	);

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(
			T_VARIABLE,
		);
	}//end register()

	/**
	 * Process this test when one of its tokens is encoutnered
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		if ( '$_SERVER' !== $tokens[ $stackPtr ]['content'] ) {
			// Not the variable we are looking for.
			return;
		}

		$variableNamePtr = $phpcsFile->findNext( array( T_CONSTANT_ENCAPSED_STRING ), ($stackPtr + 1), null, false, null, true );
		$variableName = str_replace( "'", '', $tokens[$variableNamePtr]['content'] );

		if ( false === in_array( $variableName, $this->restrictedVariables , true ) ) {
			// Not the variable we are looking for.
			return;
		}

		$phpcsFile->addError( 'Basic authentication should not be handled via PHP code.', $stackPtr, 'ServerVariables' );
	}

} // End class.

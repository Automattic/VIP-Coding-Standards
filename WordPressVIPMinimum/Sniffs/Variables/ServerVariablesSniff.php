<?php
/**
 * WordPress-VIP-Minimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\Variables;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Restricts usage of some server variables.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class ServerVariablesSniff implements Sniff {

	/**
	 * List of restricted constant names.
	 *
	 * @var array
	 */
	public $restrictedVariables = array(
		'PHP_AUTH_PW',
		'HTTP_X_IP_TRAIL',
		'HTTP_X_FORWARDED_FOR',
		'REMOTE_ADDR',
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
	}

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

		$variableNamePtr = $phpcsFile->findNext( array( T_CONSTANT_ENCAPSED_STRING ), ( $stackPtr + 1 ), null, false, null, true );
		$variableName    = str_replace( array( "'", '"' ), '', $tokens[ $variableNamePtr ]['content'] );

		if ( false === in_array( $variableName, $this->restrictedVariables, true ) ) {
			// Not the variable we are looking for.
			return;
		}

		if ( 'PHP_AUTH_PW' === $variableName ) {
			$phpcsFile->addError( 'Basic authentication should not be handled via PHP code.', $stackPtr, 'ServerVariables' );
		} elseif ( 'HTTP_X_IP_TRAIL' === $variableName || 'HTTP_X_FORWARDED_FOR' === $variableName || 'REMOTE_ADDR' === $variableName ) {
			$phpcsFile->addError(
				sprintf( 'Header "%s" is user-controlled and should be properly validated before use.', $variableName ),
				$stackPtr,
				'UserControlledHeaders'
			);
		}
	}

}

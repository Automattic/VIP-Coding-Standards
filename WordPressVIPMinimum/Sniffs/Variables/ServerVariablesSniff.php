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
	public $restrictedVariables = [
		'authVariables'           => [
			'PHP_AUTH_USER' => true,
			'PHP_AUTH_PW'   => true,
		],
		'userControlledVariables' => [
			'HTTP_X_IP_TRAIL'      => true,
			'HTTP_X_FORWARDED_FOR' => true,
			'REMOTE_ADDR'          => true,
		],
	];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [
			T_VARIABLE,
		];
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

		$variableNamePtr = $phpcsFile->findNext( [ T_CONSTANT_ENCAPSED_STRING ], ( $stackPtr + 1 ), null, false, null, true );
		$variableName    = str_replace( [ "'", '"' ], '', $tokens[ $variableNamePtr ]['content'] );

		if ( isset( $this->restrictedVariables['authVariables'][ $variableName ] ) ) {
			$message = 'Basic authentication should not be handled via PHP code.';
			$phpcsFile->addError( $message, $stackPtr, 'BasicAuthentication' );
		} elseif ( isset( $this->restrictedVariables['userControlledVariables'][ $variableName ] ) ) {
			$message = 'Header "%s" is user-controlled and should be properly validated before use.';
			$data    = [ $variableName ];
			$phpcsFile->addError( $message, $stackPtr, 'UserControlledHeaders', $data );
		}
	}

}

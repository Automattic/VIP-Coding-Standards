<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Variables;

use PHPCSUtils\Utils\TextStrings;
use WordPressVIPMinimum\Sniffs\Sniff;

/**
 * Restricts usage of some server variables.
 */
class ServerVariablesSniff extends Sniff {

	/**
	 * List of restricted indices.
	 *
	 * @var array<string, array<string, bool>>
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
	 * @return array<int|string>
	 */
	public function register() {
		return [
			T_VARIABLE,
		];
	}

	/**
	 * Process this test when one of its tokens is encountered
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		if ( $this->tokens[ $stackPtr ]['content'] !== '$_SERVER' ) {
			// Not the variable we are looking for.
			return;
		}

		$indexPtr  = $this->phpcsFile->findNext( [ T_CONSTANT_ENCAPSED_STRING ], $stackPtr + 1, null, false, null, true );
		$indexName = TextStrings::stripQuotes( $this->tokens[ $indexPtr ]['content'] );

		if ( isset( $this->restrictedVariables['authVariables'][ $indexName ] ) ) {
			$message = 'Basic authentication should not be handled via PHP code.';
			$this->phpcsFile->addError( $message, $stackPtr, 'BasicAuthentication' );
		} elseif ( isset( $this->restrictedVariables['userControlledVariables'][ $indexName ] ) ) {
			$message = 'Header "%s" is user-controlled and should be properly validated before use.';
			$data    = [ $indexName ];
			$this->phpcsFile->addError( $message, $stackPtr, 'UserControlledHeaders', $data );
		}
	}
}

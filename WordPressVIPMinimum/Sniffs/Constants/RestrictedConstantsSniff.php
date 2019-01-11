<?php
/**
 * WordPress-VIP-Minimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\Constants;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Restricts usage of some constants.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class RestrictedConstantsSniff implements Sniff {

	/**
	 * List of restricted constant names.
	 *
	 * @var array
	 */
	public $restrictedConstantNames = [
		'A8C_PROXIED_REQUEST',
	];

	/**
	 * List of restricted constant declarations.
	 *
	 * @var array
	 */
	public $restrictedConstantDeclaration = [
		'JETPACK_DEV_DEBUG',
		'WP_CRON_CONTROL_SECRET',
	];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [
			T_CONSTANT_ENCAPSED_STRING,
			T_STRING,
		];
	}

	/**
	 * Process this test when one of its tokens is encountered
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		if ( T_STRING === $tokens[ $stackPtr ]['code'] ) {
			$constantName = $tokens[ $stackPtr ]['content'];
		} else {
			$constantName = trim( $tokens[ $stackPtr ]['content'], "\"'" );
		}

		if ( false === in_array( $constantName, $this->restrictedConstantNames, true ) && false === in_array( $constantName, $this->restrictedConstantDeclaration, true ) ) {
			// Not the constant we are looking for.
			return;
		}

		if ( T_STRING === $tokens[ $stackPtr ]['code'] && true === in_array( $constantName, $this->restrictedConstantNames, true ) ) {
			$message = 'Code is touching the `%s` constant. Make sure it\'s used appropriately.';
			$data    = [ $constantName ];
			$phpcsFile->addWarning( $message, $stackPtr, 'UsingRestrictedConstant', $data );
			return;
		}

		// Find the previous non-empty token.
		$openBracket = $phpcsFile->findPrevious( Tokens::$emptyTokens, ( $stackPtr - 1 ), null, true, null, true );

		if ( T_OPEN_PARENTHESIS !== $tokens[ $openBracket ]['code'] ) {
			// Not a function call.
			return;
		}

		if ( false === isset( $tokens[ $openBracket ]['parenthesis_closer'] ) ) {
			// Not a function call.
			return;
		}

		// Find the previous non-empty token.
		$search   = Tokens::$emptyTokens;
		$search[] = T_BITWISE_AND;
		$previous = $phpcsFile->findPrevious( $search, ( $openBracket - 1 ), null, true );
		if ( T_FUNCTION === $tokens[ $previous ]['code'] ) {
			// It's a function definition, not a function call.
			return;
		}

		if ( true === in_array( $tokens[ $previous ]['code'], Tokens::$functionNameTokens, true ) ) {
			$data = [ $constantName ];
			if ( 'define' === $tokens[ $previous ]['content'] ) {
				$message = 'The definition of `%s` constant is prohibited. Please use a different name.';
				$phpcsFile->addError( $message, $previous, 'DefiningRestrictedConstant', $data );
			} elseif ( true === in_array( $constantName, $this->restrictedConstantNames, true ) ) {
				$message = 'Code is touching the `%s` constant. Make sure it\'s used appropriately.';
				$phpcsFile->addWarning( $message, $previous, 'UsingRestrictedConstant', $data );
			}
		}
	}
}

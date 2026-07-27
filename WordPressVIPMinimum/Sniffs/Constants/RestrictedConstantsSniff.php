<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Constants;

use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\TextStrings;
use WordPressVIPMinimum\Sniffs\Sniff;

/**
 * Restricts usage of some constants.
 */
class RestrictedConstantsSniff extends Sniff {

	/**
	 * List of restricted constant names.
	 *
	 * @var array<string>
	 */
	public $restrictedConstantNames = [
		'A8C_PROXIED_REQUEST',
	];

	/**
	 * List of restricted constant declarations.
	 *
	 * @var array<string>
	 */
	public $restrictedConstantDeclaration = [
		'JETPACK_DEV_DEBUG',
		'WP_CRON_CONTROL_SECRET',
	];

	/**
	 * List of (global) constant names, which should not be referenced in userland code, nor (re-)declared.
	 *
	 * {@internal The `public` versions of these properties can't be removed until the next major,
	 * though a decision is still needed whether they should be removed at all.
	 * Also see: Automattic/VIP-Coding-Standards#234 for more context and discussion about this.}
	 *
	 * @var array<string, int> Key is the constant name, value is irrelevant.
	 */
	private $restrictedConstants = [];

	/**
	 * List of (global) constants, which should not be (re-)declared, but may be referenced.
	 *
	 * {@internal The `public` versions of these properties can't be removed until the next major,
	 * though a decision is still needed whether they should be removed at all.
	 * Also see: Automattic/VIP-Coding-Standards#234 for more context and discussion about this.}
	 *
	 * @var array<string, int> Key is the constant name, value is irrelevant.
	 */
	private $restrictedRedeclaration = [];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array<int|string>
	 */
	public function register() {
		// For now, set the `private` properties based on the values of the `public` properties.
		// This should be revisited when Automattic/VIP-Coding-Standards#234 gets actioned.
		$this->restrictedConstants     = array_flip( $this->restrictedConstantNames );
		$this->restrictedRedeclaration = array_flip( $this->restrictedConstantDeclaration );

		return [
			T_CONSTANT_ENCAPSED_STRING,
			T_STRING,
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

		if ( $this->tokens[ $stackPtr ]['code'] === T_STRING ) {
			$constantName = $this->tokens[ $stackPtr ]['content'];
		} else {
			$constantName = TextStrings::stripQuotes( $this->tokens[ $stackPtr ]['content'] );
		}

		if ( isset( $this->restrictedConstants[ $constantName ] ) === false
			&& isset( $this->restrictedRedeclaration[ $constantName ] ) === false
		) {
			// Not the constant we are looking for.
			return;
		}

		if ( $this->tokens[ $stackPtr ]['code'] === T_STRING && isset( $this->restrictedConstants[ $constantName ] ) === true ) {
			$message = 'Code is touching the `%s` constant. Make sure it\'s used appropriately.';
			$data    = [ $constantName ];
			$this->phpcsFile->addWarning( $message, $stackPtr, 'UsingRestrictedConstant', $data );
			return;
		}

		// Find the previous non-empty token.
		$openBracket = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $stackPtr - 1, null, true, null, true );

		if ( $this->tokens[ $openBracket ]['code'] !== T_OPEN_PARENTHESIS ) {
			// Not a function call.
			return;
		}

		if ( isset( $this->tokens[ $openBracket ]['parenthesis_closer'] ) === false ) {
			// Not a function call.
			return;
		}

		// Find the previous non-empty token.
		$search   = Tokens::$emptyTokens;
		$search[] = T_BITWISE_AND;
		$previous = $this->phpcsFile->findPrevious( $search, $openBracket - 1, null, true );
		if ( $this->tokens[ $previous ]['code'] === T_FUNCTION ) {
			// It's a function definition, not a function call.
			return;
		}

		if ( $this->tokens[ $previous ]['code'] === T_STRING ) {
			$data = [ $constantName ];
			if ( $this->tokens[ $previous ]['content'] === 'define' ) {
				$message = 'The definition of `%s` constant is prohibited. Please use a different name.';
				$this->phpcsFile->addError( $message, $previous, 'DefiningRestrictedConstant', $data );
			} elseif ( isset( $this->restrictedConstants[ $constantName ] ) === true ) {
				$message = 'Code is touching the `%s` constant. Make sure it\'s used appropriately.';
				$this->phpcsFile->addWarning( $message, $previous, 'UsingRestrictedConstant', $data );
			}
		}
	}
}

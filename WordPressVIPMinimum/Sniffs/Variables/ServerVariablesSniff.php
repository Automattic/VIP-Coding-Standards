<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Variables;

use PHP_CodeSniffer\Util\Tokens;
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

		if ( $this->tokens[ $stackPtr ]['content'] !== '$_SERVER'
			&& $this->tokens[ $stackPtr ]['content'] !== '$GLOBALS'
		) {
			// Not a variable we are looking for.
			return;
		}

		$searchStart = $stackPtr;
		if ( $this->tokens[ $stackPtr ]['content'] === '$GLOBALS' ) {
			$globalsIndexPtr = $this->get_array_access_key( $stackPtr );
			if ( $globalsIndexPtr === false ) {
				// Couldn't find an array index token usable for the purposes of this sniff. Bow out.
				return;
			}

			$globalsIndexName = TextStrings::stripQuotes( $this->tokens[ $globalsIndexPtr ]['content'] );
			if ( $globalsIndexName !== '_SERVER' ) {
				// Not access to `$GLOBALS['_SERVER']`.
				return;
			}

			// Set the start point for the next array access key search to the close bracket of this array index.
			// No need for defensive coding as we already know there will be a valid close bracket next.
			$searchStart = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $globalsIndexPtr + 1 ), null, true );
		}

		$prevNonEmpty = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, ( $stackPtr - 1 ), null, true );
		if ( $this->tokens[ $prevNonEmpty ]['code'] === T_DOUBLE_COLON ) {
			// Access to OO property mirroring the name of the superglobal. Not our concern.
			return;
		}

		$indexPtr = $this->get_array_access_key( $searchStart );
		if ( $indexPtr === false ) {
			// Couldn't find an array index token usable for the purposes of this sniff. Bow out as undetermined.
			return;
		}

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

	/**
	 * Get the array access key.
	 *
	 * Find the array access key and check if it is:
	 * - comprised of a single functional token.
	 * - that token is a T_CONSTANT_ENCAPSED_STRING.
	 *
	 * @param int $stackPtr The position of either a variable or the close bracket of a previous array access.
	 *
	 * @return int|false Stack pointer to the index token; or FALSE for
	 *                   live coding, non-indexed array assignment, or non plain text array keys.
	 */
	private function get_array_access_key( $stackPtr ) {
		$openBracket = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );
		if ( $openBracket === false
			|| $this->tokens[ $openBracket ]['code'] !== T_OPEN_SQUARE_BRACKET
			|| isset( $this->tokens[ $openBracket ]['bracket_closer'] ) === false
		) {
			// If it isn't an open bracket, this isn't array access. And without closer, it is a parse error/live coding.
			return false;
		}

		$closeBracket = $this->tokens[ $openBracket ]['bracket_closer'];

		$indexPtr = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $openBracket + 1 ), $closeBracket, true );
		if ( $indexPtr === false
			|| $this->tokens[ $indexPtr ]['code'] !== T_CONSTANT_ENCAPSED_STRING
		) {
			// No array access (like for array assignment without key) or key is not plain text.
			return false;
		}

		$hasOtherTokens = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $indexPtr + 1 ), $closeBracket, true );
		if ( $hasOtherTokens !== false ) {
			// The array index is comprised of multiple tokens. Bow out as undetermined.
			return false;
		}

		return $indexPtr;
	}
}

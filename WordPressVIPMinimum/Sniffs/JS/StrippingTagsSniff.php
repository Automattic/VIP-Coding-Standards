<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\JS;

use PHP_CodeSniffer\Sniffs\DeprecatedSniff;
use PHP_CodeSniffer\Util\Tokens;
use WordPressVIPMinimum\Sniffs\Sniff;

/**
 * WordPressVIPMinimum_Sniffs_JS_StrippingTagsSniff.
 *
 * Looks for incorrect way of stripping tags.
 */
class StrippingTagsSniff extends Sniff implements DeprecatedSniff {

	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var string[]
	 */
	public $supportedTokenizers = [ 'JS' ];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array<int|string>
	 */
	public function register() {
		return [
			T_STRING,
		];
	}


	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		if ( $this->tokens[ $stackPtr ]['content'] !== 'html' ) {
			// Looking for html() only.
			return;
		}

		$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true );

		if ( $this->tokens[ $nextToken ]['code'] !== T_OPEN_PARENTHESIS ) {
			// Not a function.
			return;
		}

		$afterFunctionCall = $this->phpcsFile->findNext( Tokens::$emptyTokens, $this->tokens[ $nextToken ]['parenthesis_closer'] + 1, null, true, null, true );

		if ( $this->tokens[ $afterFunctionCall ]['code'] !== T_OBJECT_OPERATOR ) {
			return;
		}

		$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $afterFunctionCall + 1, null, true, null, true );

		if ( $this->tokens[ $nextToken ]['code'] === T_STRING && $this->tokens[ $nextToken ]['content'] === 'text' ) {
			$message = 'Vulnerable tag stripping approach detected.';
			$this->phpcsFile->addError( $message, $stackPtr, 'VulnerableTagStripping' );
		}
	}

	/**
	 * Provide the version number in which the sniff was deprecated.
	 *
	 * @return string
	 */
	public function getDeprecationVersion() {
		return 'VIP-Coding-Standard v3.1.0';
	}

	/**
	 * Provide the version number in which the sniff will be removed.
	 *
	 * @return string
	 */
	public function getRemovalVersion() {
		return 'VIP-Coding-Standard v4.0.0';
	}

	/**
	 * Provide a custom message to display with the deprecation.
	 *
	 * @return string
	 */
	public function getDeprecationMessage() {
		return 'Support for scanning JavaScript files will be removed from PHP_CodeSniffer, so this sniff is no longer viable.';
	}
}

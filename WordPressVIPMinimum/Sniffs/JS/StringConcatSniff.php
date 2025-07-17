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
 * WordPressVIPMinimum_Sniffs_JS_StringConcatSniff.
 *
 * Looks for HTML string concatenation.
 */
class StringConcatSniff extends Sniff implements DeprecatedSniff {

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
			T_PLUS,
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

		$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true );

		if ( $this->tokens[ $nextToken ]['code'] === T_CONSTANT_ENCAPSED_STRING && strpos( $this->tokens[ $nextToken ]['content'], '<' ) !== false && preg_match( '/\<\/[a-zA-Z]+/', $this->tokens[ $nextToken ]['content'] ) === 1 ) {
			$data = [ '+' . $this->tokens[ $nextToken ]['content'] ];
			$this->addFoundError( $stackPtr, $data );
		}

		$prevToken = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $stackPtr - 1, null, true, null, true );

		if ( $this->tokens[ $prevToken ]['code'] === T_CONSTANT_ENCAPSED_STRING && strpos( $this->tokens[ $prevToken ]['content'], '<' ) !== false && preg_match( '/\<[a-zA-Z]+/', $this->tokens[ $prevToken ]['content'] ) === 1 ) {
			$data = [ $this->tokens[ $nextToken ]['content'] . '+' ];
			$this->addFoundError( $stackPtr, $data );
		}
	}

	/**
	 * Consolidated violation.
	 *
	 * @param int           $stackPtr The position of the current token in the stack passed in $tokens.
	 * @param array<string> $data     Replacements for the error message.
	 *
	 * @return void
	 */
	private function addFoundError( $stackPtr, array $data ) {
		$message = 'HTML string concatenation detected, this is a security risk, use DOM node construction or a templating language instead: %s.';
		$this->phpcsFile->addError( $message, $stackPtr, 'Found', $data );
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

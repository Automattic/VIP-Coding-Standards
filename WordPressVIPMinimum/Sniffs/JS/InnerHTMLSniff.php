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
 * WordPressVIPMinimum_Sniffs_JS_InnerHTMLSniff.
 *
 * Looks for instances of .innerHMTL.
 */
class InnerHTMLSniff extends Sniff implements DeprecatedSniff {

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

		if ( $this->tokens[ $stackPtr ]['content'] !== 'innerHTML' ) {
			// Looking for .innerHTML only.
			return;
		}

		$prevToken = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $stackPtr - 1, null, true, null, true );

		if ( $this->tokens[ $prevToken ]['code'] !== T_OBJECT_OPERATOR ) {
				return;
		}

		$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true );

		if ( $this->tokens[ $nextToken ]['code'] !== T_EQUAL ) {
			// Not an assignment.
			return;
		}

		$nextToken     = $this->phpcsFile->findNext( Tokens::$emptyTokens, $nextToken + 1, null, true, null, true );
		$foundVariable = false;

		while ( $nextToken !== false && $this->tokens[ $nextToken ]['code'] !== T_SEMICOLON ) {

			if ( $this->tokens[ $nextToken ]['code'] === T_STRING ) {
				$foundVariable = true;
				break;
			}

			$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $nextToken + 1, null, true, null, true );
		}

		if ( $foundVariable === true ) {
			$message = 'Any HTML passed to `%s` gets executed. Consider using `.textContent` or make sure that used variables are properly escaped.';
			$data    = [ $this->tokens[ $stackPtr ]['content'] ];
			$this->phpcsFile->addWarning( $message, $stackPtr, 'Found', $data );
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

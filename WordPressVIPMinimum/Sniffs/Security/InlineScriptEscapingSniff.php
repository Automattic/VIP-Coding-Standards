<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\Security;

use WordPressVIPMinimum\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Checks whether proper escaping function is used between script tags.
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class InlineScriptEscapingSniff extends Sniff {

	/**
	 * Property to keep track of between start and close script tags.
	 *
	 * @var array
	 */
	private $in_script = false;

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [
			'T_INLINE_HTML' => T_INLINE_HTML,
			'T_STRING'      => T_STRING,
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
		$content = trim( $this->tokens[ $stackPtr ]['content'] );

		if ( $content === '' ) {
			return;
		}

		if ( $this->has_open_script_tag( $content ) === true ) {
			$this->in_script = true;
		} elseif ( strpos( '</script>', $content ) !== false ) {
			$this->in_script = false;
		}

		if ( $this->in_script === true && $content === 'esc_js' ) {
			$message = 'Please do not use `esc_js()` for inline script escaping. See our code repository for 
			examples on how to escape within: https://github.com/Automattic/vip-code-samples/blob/master/10-security/js-dynamic.php';
			$this->phpcsFile->addError( $message, $stackPtr, 'InlineScriptEsc' );
			return;
		}
	}

	/**
	 * Check if a content string contains start <script> tag without closing one.
	 *
	 * @param string $content Haystack where we look for <script> tag.
	 *
	 * @return bool True if the string contains only start <script> tag, false otherwise.
	 */
	public function has_open_script_tag( $content ) {
		if ( substr( $content, -1 ) !== '>' || strpos( $content, '</script>' ) !== false ) {
			// Incomplete or has closing tag, bail.
			return false;
		}

		return strpos( $content, '<script' ) !== false;
	}
}

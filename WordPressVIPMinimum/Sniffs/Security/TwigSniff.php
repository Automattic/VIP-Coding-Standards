<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Security;

use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\TextStrings;
use WordPressVIPMinimum\Sniffs\Sniff;

/**
 * Looks for instances of unescaped output for Twig templating engine.
 */
class TwigSniff extends Sniff {

	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var string[]
	 */
	public $supportedTokenizers = [ 'JS', 'PHP' ];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array<int|string>
	 */
	public function register() {
		return Tokens::$textStringTokens;
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {
		// Strip any potentially interpolated expressions.
		$only_text = $this->tokens[ $stackPtr ]['content'];
		if ( $this->tokens[ $stackPtr ]['code'] === T_DOUBLE_QUOTED_STRING
			|| $this->tokens[ $stackPtr ]['code'] === T_HEREDOC
		) {
			$only_text = TextStrings::stripEmbeds( $only_text );
		}

		if ( preg_match( '/autoescape\s+false/', $only_text ) === 1 ) {
			// Twig autoescape disabled.
			$message = 'Found Twig autoescape disabling notation.';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'AutoescapeFalse' );
		}

		if ( preg_match( '/\|\s*raw/', $only_text ) === 1 ) {
			// Twig default unescape filter.
			$message = 'Found Twig default unescape filter: "|raw".';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'RawFound' );
		}
	}
}

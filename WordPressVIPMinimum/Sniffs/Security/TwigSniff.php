<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\Security;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Looks for instances of unescaped output for Twig templating engine.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class TwigSniff implements Sniff {

	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var string[]
	 */
	public $supportedTokenizers = [ 'JS', 'PHP' ];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [
			T_CONSTANT_ENCAPSED_STRING,
			T_INLINE_HTML,
			T_HEREDOC,
		];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		if ( 1 === preg_match( '/autoescape\s+false/', $tokens[ $stackPtr ]['content'] ) ) {
			// Twig autoescape disabled.
			$message = 'Found Twig autoescape disabling notation.';
			$phpcsFile->addWarning( $message, $stackPtr, 'AutoescapeFalse' );
		}

		if ( 1 === preg_match( '/\|\s*raw/', $tokens[ $stackPtr ]['content'] ) ) {
			// Twig default unescape filter.
			$message = 'Found Twig default unescape filter: "|raw".';
			$phpcsFile->addWarning( $message, $stackPtr, 'RawFound' );
		}
	}

}

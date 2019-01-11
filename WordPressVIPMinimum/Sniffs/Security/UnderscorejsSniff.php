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
 * Looks for instances of unescaped output for Underscore.js templating engine.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class UnderscorejsSniff implements Sniff {

	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var array
	 */
	public $supportedTokenizers = [
		'JS',
		'PHP',
	];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [
			T_CONSTANT_ENCAPSED_STRING,
			T_PROPERTY,
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

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], '<%=' ) ) {
			// Underscore.js unescaped output.
			$message = 'Found Underscore.js unescaped output notation: "<%=".';
			$phpcsFile->addWarning( $message, $stackPtr, 'OutputNotation' );
		}

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], 'interpolate' ) ) {
			// Underscore.js unescaped output.
			$message = 'Found Underscore.js delimiter change notation.';
			$phpcsFile->addWarning( $message, $stackPtr, 'InterpolateFound' );
		}
	}

}

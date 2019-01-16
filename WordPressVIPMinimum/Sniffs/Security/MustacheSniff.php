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
 * Looks for instances of unescaped output for Mustache templating engine and Handlebars.js.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class MustacheSniff implements Sniff {

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
			T_STRING,
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

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], '{{{' ) || false !== strpos( $tokens[ $stackPtr ]['content'], '}}}' ) ) {
			// Mustache unescaped output notation.
			$message = 'Found Mustache unescaped output notation: "{{{}}}".';
			$phpcsFile->addWarning( $message, $stackPtr, 'OutputNotation' );
		}

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], '{{&' ) ) {
			// Mustache unescaped variable notation.
			$message = 'Found Mustache unescape variable notation: "{{&".';
			$phpcsFile->addWarning( $message, $stackPtr, 'VariableNotation' );
		}

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], '{{=' ) ) {
			// Mustache delimiter change.
			$new_delimiter = trim( str_replace( [ '{{=', '=}}' ], '', substr( $tokens[ $stackPtr ]['content'], 0, strpos( $tokens[ $stackPtr ]['content'], '=}}' ) + 3 ) ) );
			$message       = 'Found Mustache delimiter change notation. New delimiter is: %s.';
			$data          = [ $new_delimiter ];
			$phpcsFile->addWarning( $message, $stackPtr, 'DelimiterChange', $data );
		}

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], 'SafeString' ) ) {
			// Handlebars.js Handlebars.SafeString does not get escaped.
			$message = 'Found Handlebars.SafeString call which does not get escaped.';
			$phpcsFile->addWarning( $message, $stackPtr, 'SafeString' );
		}
	}

}
<?php
/**
 * WordPressVIPMinimum_Sniffs_TemplatingEngines_UnescapedOutputMustacheSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\TemplatingEngines;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * WordPressVIPMinimum_Sniffs_TemplatingEngines_UnescapedOutputMustacheSniff.
 *
 * Looks for instances of unescaped output for Mustache templating engine and Handlebars.js.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class UnescapedOutputMustacheSniff implements Sniff {

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
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current token in the
	 *                                               stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], '{{{' ) || false !== strpos( $tokens[ $stackPtr ]['content'], '}}}' ) ) {
			// Mustache unescaped output notation.
			$phpcsFile->addWarning( 'Found Mustache unescaped output notation: "{{{}}}".', $stackPtr, 'OutputNotation' );
		}

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], '{{&' ) ) {
			// Mustache unescaped variable notation.
			$phpcsFile->addWarning( 'Found Mustache unescape variable notation: "{{&".', $stackPtr, 'VariableNotation' );
		}

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], '{{=' ) ) {
			// Mustache delimiter change.
			$new_delimiter = trim( str_replace( [ '{{=', '=}}' ], '', substr( $tokens[ $stackPtr ]['content'], 0, ( strpos( $tokens[ $stackPtr ]['content'], '=}}' ) + 3 ) ) ) );
			$phpcsFile->addWarning( sprintf( 'Found Mustache delimiter change notation. New delimiter is: %s', $new_delimiter ), $stackPtr, 'delimiterChange' );
		}

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], 'SafeString' ) ) {
			// Handlebars.js Handlebars.SafeString does not get escaped.
			$phpcsFile->addWarning( 'Found Handlebars.SafeString call which does not get escaped.', $stackPtr, 'SafeString' );
		}
	}

}

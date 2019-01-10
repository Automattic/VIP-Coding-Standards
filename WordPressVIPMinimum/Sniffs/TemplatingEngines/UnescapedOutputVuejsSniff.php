<?php
/**
 * WordPressVIPMinimum_Sniffs_TemplatingEngines_UnescapedOutputTwigSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\TemplatingEngines;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * WordPressVIPMinimum_Sniffs_TemplatingEngines_UnescapedOutputVuejsSniff.
 *
 * Looks for instances of unescaped output for Twig templating engine.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class UnescapedOutputVuejsSniff implements Sniff {

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
			T_INLINE_HTML,
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

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], 'v-html' ) ) {
			// Vue autoescape disabled.
			$message = 'Found Vue.js non-escaped (raw) HTML directive.';
			$phpcsFile->addWarning( $message, $stackPtr, 'RawHTMLDirectiveFound' );
		}
	}

}

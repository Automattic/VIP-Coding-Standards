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
	public $supportedTokenizers = array(
		'JS',
		'PHP',
	);

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(
			T_CONSTANT_ENCAPSED_STRING,
			T_INLINE_HTML,
		);
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

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], 'v-html' ) ) {
			// Twig autoescape disabled.
			$phpcsFile->addWarning( 'Found Vue.js non-escaped (raw) HTML directive.', $stackPtr, 'v-html' );
		}
	}

}

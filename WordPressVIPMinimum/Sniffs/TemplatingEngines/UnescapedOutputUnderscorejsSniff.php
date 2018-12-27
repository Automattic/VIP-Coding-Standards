<?php
/**
 * WordPressVIPMinimum_Sniffs_TemplatingEngines_UnescapedOutputUnderscorejsSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\TemplatingEngines;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * WordPressVIPMinimum_Sniffs_TemplatingEngines_UnescapedOutputUnderscorejsSniff.
 *
 * Looks for instances of unescaped output for Underscore.js templating engine.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class UnescapedOutputUnderscorejsSniff implements Sniff {

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
			T_PROPERTY,
			T_INLINE_HTML,
			T_HEREDOC,
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

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], '<%=' ) ) {
			// Underscore.js unescaped output.
			$phpcsFile->addWarning( 'Found Underscore.js unescaped output notation: "<%=".', $stackPtr, 'OutputNotation' );
		}

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], 'interpolate' ) ) {
			// Underscore.js unescaped output.
			$phpcsFile->addWarning( 'Found Underscore.js delimiter change notation.', $stackPtr, 'interpolate' );
		}
	}

}

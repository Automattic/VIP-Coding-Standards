<?php
/**
 * WordPressVIPMinimum_Sniffs_TemplatingEngines_UnescapedOutputMustacheSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\TemplatingEngines;

use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * WordPressVIPMinimum_Sniffs_TemplatingEngines_UnescapedOutputMustacheSniff.
 *
 * Looks for instances of unescaped output for Mustache templating engine and Handlebars.js.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class UnescapedOutputMustacheSniff implements \PHP_CodeSniffer_Sniff {

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
			T_STRING,
			T_INLINE_HTML,
			T_HEREDOC,
		);

	}//end register()

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
			// Mustache unescaped output notation
			$phpcsFile->addWarning( 'Found Mustache unescaped output notation: "{{{}}}".', $stackPtr, '{{{' );
		}

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], '{{&' ) ) {
			// Mustache unescaped variable notation
			$phpcsFile->addWarning( 'Found Mustache unescape variable notation: "{{&".', $stackPtr, '{{&' );
		}

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], '{{=' ) ) {
			// Mustache delimiter change
			$new_delimiter = trim( str_replace( array( '{{=', '=}}' ), '', substr( $tokens[ $stackPtr ]['content'], 0, ( strpos( $tokens[ $stackPtr ]['content'], '=}}' ) + 3 ) ) ) );
			$phpcsFile->addWarning( sprintf( 'Found Mustache delimiter change notation. New delimiter is: %s', $new_delimiter ), $stackPtr, 'delimiterChange' );
		}

		if ( false !== strpos( $tokens[ $stackPtr ]['content'], 'SafeString' ) ) {
			// Handlebars.js Handlebars.SafeString does not get escaped
			$phpcsFile->addWarning( 'Found Handlebars.SafeString call which does not get escaped.', $stackPtr, 'SafeString' );
		}

	}//end process()

}//end class

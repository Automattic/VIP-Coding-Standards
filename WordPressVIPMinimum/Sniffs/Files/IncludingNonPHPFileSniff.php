<?php
/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingNonPHPFileSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Files;

use PHP_CodeSniffer\Files\File;
use WordPressVIPMinimum\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingNonPHPFileSniff.
 *
 * Checks that __DIR__, dirname( __FILE__ ) or plugin_dir_path( __FILE__ )
 * is used when including or requiring files.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class IncludingNonPHPFileSniff extends Sniff {

	/**
	 * File extensions used for PHP files.
	 *
	 * Files with these extensions are allowed to be `include`d.
	 *
	 * @var array Key is the extension, value is irrelevant.
	 */
	private $php_extensions = [
		'php'  => true,
		'inc'  => true,
		'phar' => true,
	];

	/**
	 * File extensions used for SVG and CSS files.
	 *
	 * @var array Key is the extension, value is irrelevant.
	 */
	private $svg_css_extensions = [
		'css' => true,
		'svg' => true,
	];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return Tokens::$includeTokens;
	}


	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {
		$curStackPtr      = $stackPtr;
		$end_of_statement = $this->phpcsFile->findEndOfStatement( $stackPtr );
		while ( $this->phpcsFile->findNext( Tokens::$stringTokens, $curStackPtr + 1, $end_of_statement + 1 ) !== false ) {
			$curStackPtr = $this->phpcsFile->findNext( Tokens::$stringTokens, $curStackPtr + 1, $end_of_statement + 1 );

			$stringWithoutEnclosingQuotationMarks = trim( $this->tokens[ $curStackPtr ]['content'], "\"'" );

			$isFileName = preg_match( '`\.([a-z]{2,})$`i', $stringWithoutEnclosingQuotationMarks, $regexMatches );

			if ( $isFileName === false || $isFileName === 0 ) {
				continue;
			}

			$extension = strtolower( $regexMatches[1] );
			if ( isset( $this->php_extensions[ $extension ] ) === true ) {
				return;
			}

			$message = 'Local non-PHP file should be loaded via `file_get_contents` rather than via `%s`. Found: %s';
			$data    = [
				strtolower( $this->tokens[ $stackPtr ]['content'] ),
				$this->tokens[ $curStackPtr ]['content'],
			];
			$code    = 'IncludingNonPHPFile';

			if ( isset( $this->svg_css_extensions[ $extension ] ) === true ) {
				// Be more specific for SVG and CSS files.
				$message = 'Local SVG and CSS files should be loaded via `file_get_contents` rather than via `%s`. Found: %s';
				$code    = 'IncludingSVGCSSFile';
			}

			$this->phpcsFile->addError( $message, $curStackPtr, $code, $data );
		}
	}

}

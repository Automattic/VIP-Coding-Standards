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
		$curStackPtr = $stackPtr;
		while ( $this->phpcsFile->findNext( Tokens::$stringTokens, $curStackPtr + 1, null, false, null, true ) !== false ) {
			$curStackPtr = $this->phpcsFile->findNext( Tokens::$stringTokens, $curStackPtr + 1, null, false, null, true );

			if ( $this->tokens[ $curStackPtr ]['code'] === T_CONSTANT_ENCAPSED_STRING ) {
				$stringWithoutEnclosingQuotationMarks = trim( $this->tokens[ $curStackPtr ]['content'], "\"'" );
			} else {
				$stringWithoutEnclosingQuotationMarks = $this->tokens[ $curStackPtr ]['content'];
			}

			$isFileName = preg_match( '/.*(\.[a-z]{2,})$/i', $stringWithoutEnclosingQuotationMarks, $regexMatches );

			if ( $isFileName === false || $isFileName === 0 ) {
				continue;
			}

			$extension = $regexMatches[1];
			if ( in_array( $extension, [ '.php', '.inc' ], true ) === true ) {
				return;
			}

			$message = 'Local non-PHP file should be loaded via `file_get_contents` rather than via `%s`.';
			$data    = [ $this->tokens[ $stackPtr ]['content'] ];
			$code    = 'IncludingNonPHPFile';

			if ( in_array( $extension, [ '.svg', '.css' ], true ) === true ) {
				// Be more specific for SVG and CSS files.
				$message = 'Local SVG and CSS files should be loaded via `file_get_contents` rather than via `%s`.';
				$code    = 'IncludingSVGCSSFile';
			}

			$this->phpcsFile->addError( $message, $curStackPtr, $code, $data );
		}
	}

}

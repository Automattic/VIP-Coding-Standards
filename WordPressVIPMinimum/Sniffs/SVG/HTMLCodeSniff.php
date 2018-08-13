<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\SVG;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * This function generates a warning when
 * <?php or <? is found in a SVG file.
 *
 * The aim is to bring this to the notice of
 * reviewers, so they can make sure the PHP-code
 * is safe. The reason for this is that SVG files
 * are often included in PHP files, but manually
 * scanning SVG files takes alot of time and is prone
 * to errors.
 */
class HTMLCodeSniff implements Sniff {

	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * We want everything related to opening PHP tags.
	 *
	 * @return array(int)
	 */
	public function register() {
		return array(
			T_OPEN_TAG,
			T_OPEN_TAG_WITH_ECHO,
		);
	}

	/**
	 * Processes the tokens that this sniff is interested in.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file where the token was found.
	 * @param int                         $stackPtr  The position in the stack where
	 *                                               the token was found.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		print_r($tokens);

		// Make sure it is a SVG file.
		$file_extension = strtolower(
			pathinfo(
				$phpcsFile->path,
				PATHINFO_EXTENSION
			)
		);

		// If not SVG file, ignore.
		if ( 'svg' !== $file_extension ) {
			return;
		}

		$phpcsFile->addWarning(
			'<?php or <?= found in SVG file, needs to be reviewed',
			$stackPtr,
			'HTMLCode'
		);
	}
}

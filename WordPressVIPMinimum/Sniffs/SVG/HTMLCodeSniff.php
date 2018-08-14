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
	 * Returns the file extension of a given file.
	 *
	 * @param string $file_path Path to file.
	 *
	 * @return string
	 */
	private function get_file_extension( $file_path ) {
		return strtolower(
			pathinfo(
				$file_path,
				PATHINFO_EXTENSION
			)
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

		// Get file extension.
		$file_extension = $this->get_file_extension(
			$phpcsFile->path
		);

		// If not SVG file, ignore.
		if ( 'svg' !== $file_extension ) {
			return;
		}

		$phpcsFile->addWarning(
			'<?php or <?= found in SVG file, needs to be reviewed manually',
			$stackPtr,
			'HTMLCode'
		);
	}
}

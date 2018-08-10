<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\SVG;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * This function generates an error when
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
	 * A list of tokenizers this sniff supports.
	 *
	 * @var array
	 */
	public $supportedTokenizers = array(
		'PHP',
	);

	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * We want everything function-related.
	 *
	 * @return array(int)
	 */
	public function register() {
		return array(
			T_OPEN_TAG,
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
		$tokens	= $phpcsFile->getTokens();

		$nxt = $phpcsFile->findNext(
			T_OPEN_TAG,
			( $stackPtr ),
			null,
			false,
			null,
			true
		);

		$tokens[ $nxt ]['content'] =
			strtolower( $tokens[ $nxt ]['content'] );

		$found1 =
			strpos( $tokens[ $nxt ]['content'], '<?php' );

		$found2 =
			strpos( $tokens[ $nxt ]['content'], '<?' );

		if (
			( false !== $found1 ) ||
			( false !== $found2 )
		) {
			$phpcsFile->addWarning(
				'<?php or <? found in SVG file',
				$nxt,
				'HTMLCode'
			);
		}
	}
}

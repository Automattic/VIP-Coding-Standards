<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Filters;

use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * This sniff restricts usage of some filters
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class RestrictedFilterSniff implements \PHP_CodeSniffer_Sniff {

	/**
	 * List of restricted filter names.
	 *
	 * @var array
	 */
	public $restrictedFilters = array(
		'upload_mimes' => true,
	);

	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * @return array(int)
	 */
	public function register() {
		return Tokens::$functionNameTokens;
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

		$functionName = $tokens[ $stackPtr ]['content'];

		if ( 'add_filter' !== $functionName ) {
			return;
		}

		$filterNamePtr = $phpcsFile->findNext(
			array_merge( Tokens::$emptyTokens, array( T_OPEN_PARENTHESIS ) ), // types.
			$stackPtr + 1, // start.
			null, // end.
			true, // exclude.
			null, // value.
			true // local.
		);

		$filterName = str_replace( array( "'", '"' ), '', $tokens[ $filterNamePtr ]['content'] );

		if ( isset( $this->restrictedFilters[ $filterName ] ) && 'upload_mimes' === $filterName ) {
			$phpcsFile->addWarning( 'Please ensure that the mimes being filtered do not include insecure types (e.g. SVG). Manual inspection required.', $stackPtr, 'UploadMimesRestrictedFilter' );
		}
	}
}

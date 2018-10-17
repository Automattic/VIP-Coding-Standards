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
 * This sniff restricts usage of some filters.
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since 0.4.0
 */
class RestrictedFilterSniff implements \PHP_CodeSniffer_Sniff {

	/**
	 * List of restricted filter names.
	 *
	 * @var array
	 */
	private $restrictedFilters = array(
		'upload_mimes' => true,
	);

	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * @return array<int, int>
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
			array_merge( Tokens::$emptyTokens, array( T_OPEN_PARENTHESIS ) ),
			$stackPtr + 1,
			null,
			true,
			null,
			true
		);

		$endHookPtr = $phpcsFile->findNext( 
			T_COMMA,
			$filterNamePtr,
			null,
			false,
			null, 
			true
		);

		$filterName = $this->transformString( $tokens[ $filterNamePtr ]['content'] );

		// if concatenation is found, build $filterName.
		$concatPtr = $phpcsFile->findNext(
			T_STRING_CONCAT,
			$filterNamePtr,
			$endHookPtr,
			false,
			null, 
			true
		);
		$concatenation = $concatPtr ? true: false; 
		if ( $concatenation ) { 
			for ( $i = $filterNamePtr + 1; $i < $endHookPtr; $i++ ) {
				if ( T_CONSTANT_ENCAPSED_STRING === $tokens[ $i ]['code'] ) {
					$filterName .= $this->transformString( $tokens[ $i ]['content'] );
				}
			}
		}

		if ( isset( $this->restrictedFilters[ $filterName ] ) && 'upload_mimes' === $filterName ) {
			$phpcsFile->addWarning( 'Please ensure that the mimes being filtered do not include insecure types (e.g. SVG). Manual inspection required.', $stackPtr, 'UploadMimesRestrictedFilter' );
		}
	}

	/**
	 * Transform string.
	 *
	 * @param string $string The name of the filter.
	 *
	 * @return string
	 */
	private function transformString( $string ) {
		$string = str_replace( array( "'", '"' ), '', $string ); // remove quotes (double and single).
		$string = strtolower( $string ); // make sure we don't have weird caps.

		return $string;
	}
}

<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\Performance;

use WordPressCS\WordPress\AbstractArrayAssignmentRestrictionsSniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Flag suspicious WP_Query and get_posts params.
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class WPQueryParamsSniff extends AbstractArrayAssignmentRestrictionsSniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		$targets = parent::register();

		// Add the target for the "old" implementation.
		$targets[] = T_CONSTANT_ENCAPSED_STRING;

		return $targets;
	}

	/**
	 * Groups of variables to restrict.
	 * This should be overridden in extending classes.
	 *
	 * Example: groups => array(
	 *  'groupname' => array(
	 *      'type'     => 'error' | 'warning',
	 *      'message'  => 'Dont use this one please!',
	 *      'keys'     => array( 'key1', 'another_key' ),
	 *      'callback' => array( 'class', 'method' ), // Optional.
	 *  )
	 * )
	 *
	 * @return array
	 */
	public function getGroups() {
		return [
			'PostNotIn' => [
				'type'    => 'warning',
				'message' => 'Using `exclude`, which is subsequently used by `post__not_in`, should be done with caution, see https://docs.wpvip.com/how-tos/improve-performance-by-removing-usage-of-post__not_in/ for more information.',
				'keys'    => [
					'exclude',
				],
			],
		];
	}

	/**
	 * Process this test when one of its tokens is encountered
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		if ( trim( $this->tokens[ $stackPtr ]['content'], '\'' ) === 'suppress_filters' ) {

			$next_token = $this->phpcsFile->findNext( array_merge( Tokens::$emptyTokens, [ T_EQUAL, T_CLOSE_SQUARE_BRACKET, T_DOUBLE_ARROW ] ), $stackPtr + 1, null, true );

			if ( $this->tokens[ $next_token ]['code'] === T_TRUE ) {
				// https://docs.wpvip.com/technical-references/caching/uncached-functions/.
				$message = 'Setting `suppress_filters` to `true` is prohibited.';
				$this->phpcsFile->addError( $message, $stackPtr, 'SuppressFiltersTrue' );
			}
		}

		if ( trim( $this->tokens[ $stackPtr ]['content'], '\'' ) === 'post__not_in' ) {
			$message = 'Using `post__not_in` should be done with caution, see https://docs.wpvip.com/how-tos/improve-performance-by-removing-usage-of-post__not_in/ for more information.';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'PostNotIn' );
		}

		parent::process_token( $stackPtr );
	}

	/**
	 * Callback to process a confirmed key which doesn't need custom logic, but should always error.
	 *
	 * @param  string $key   Array index / key.
	 * @param  mixed  $val   Assigned value.
	 * @param  int    $line  Token line.
	 * @param  array  $group Group definition.
	 * @return mixed         FALSE if no match, TRUE if matches, STRING if matches
	 *                       with custom error message passed to ->process().
	 */
	public function callback( $key, $val, $line, $group ) {
		return true;
	}

}

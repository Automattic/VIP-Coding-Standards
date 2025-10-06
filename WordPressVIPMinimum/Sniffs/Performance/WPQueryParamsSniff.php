<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Performance;

use WordPressCS\WordPress\AbstractArrayAssignmentRestrictionsSniff;
use WordPressCS\WordPress\Helpers\ContextHelper;

/**
 * Flag suspicious WP_Query and get_posts params.
 *
 * @link https://docs.wpvip.com/technical-references/caching/uncached-functions/
 */
class WPQueryParamsSniff extends AbstractArrayAssignmentRestrictionsSniff {

	/**
	 * Whether the current $stackPtr being scanned is nested in a function call to get_users().
	 *
	 * @var bool
	 */
	private $in_get_users = false;

	/**
	 * Groups of variables to restrict.
	 *
	 * @return array<string, array<string, string|array<string>>>
	 */
	public function getGroups() {
		return [
			// WordPress.com: https://lobby.vip.wordpress.com/wordpress-com-documentation/uncached-functions/.
			// VIP Go: https://wpvip.com/documentation/vip-go/uncached-functions/.
			'SuppressFilters' => [
				'name'    => 'SuppressFilters',
				'type'    => 'error',
				'message' => 'Setting `suppress_filters` to `true` is prohibited.',
				'keys'    => [
					'suppress_filters',
				],
			],
			'PostNotIn' => [
				'name'    => 'PostNotIn',
				'type'    => 'warning',
				'message' => 'Using exclusionary parameters, like %s, in calls to get_posts() should be done with caution, see https://docs.wpvip.com/databases/optimize-queries/using-post__not_in/ for more information.',
				'keys'    => [
					'post__not_in',
					'exclude',
				],
			],
		];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {
		$this->in_get_users = ContextHelper::is_in_function_call( $this->phpcsFile, $stackPtr, [ 'get_users' => true ], true, true );

		parent::process_token( $stackPtr );
	}


	/**
	 * Callback to process a confirmed key which doesn't need custom logic, but should always error.
	 *
	 * @param  string $key   Array index / key.
	 * @param  mixed  $val   Assigned value.
	 * @param  int    $line  Token line.
	 * @param  array  $group Group definition.
	 *
	 * @return bool FALSE if no match, TRUE if matches.
	 */
	public function callback( $key, $val, $line, $group ) {
		switch ( $group['name'] ) {
			case 'SuppressFilters':
				return ( $val === 'true' );

			case 'PostNotIn':
				if ( $key === 'exclude' && $this->in_get_users !== false ) {
					// This is not an array used by get_posts(). See #672.
					return false;
				}

				return true;

			default:
				return false;
		}
	}
}

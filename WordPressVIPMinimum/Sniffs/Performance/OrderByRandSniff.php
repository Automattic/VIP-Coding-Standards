<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace WordPressVIPMinimum\Sniffs\Performance;

use WordPressCS\WordPress\AbstractArrayAssignmentRestrictionsSniff;

/**
 * Flag using orderby => rand.
 *
 * @link https://docs.wpvip.com/technical-references/code-review/#order-by-rand
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since   0.5.0
 */
class OrderByRandSniff extends AbstractArrayAssignmentRestrictionsSniff {

	/**
	 * Groups of variables to restrict.
	 *
	 * @return array
	 */
	public function getGroups() {
		return [
			'orderby' => [
				'type' => 'error',
				'keys' => [
					'orderby',
				],
			],
		];
	}

	/**
	 * Callback to process each confirmed key, to check value
	 * This must be extended to add the logic to check assignment value
	 *
	 * @param  string $key   Array index / key.
	 * @param  mixed  $val   Assigned value.
	 * @param  int    $line  Token line.
	 * @param  array  $group Group definition.
	 * @return mixed         FALSE if no match, TRUE if matches, STRING if matches with custom error message passed to ->process().
	 */
	public function callback( $key, $val, $line, $group ) {
		if ( strtolower( $val ) === 'rand' ) {
			return 'Detected forbidden query_var "%s" of "%s". Use vip_get_random_posts() instead.';
		}

		return false;
	}

}

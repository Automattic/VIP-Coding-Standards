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
 * Flag returning high or infinite posts_per_page.
 *
 * @link https://docs.wpvip.com/technical-references/code-review/#no-limit-queries
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since   0.5.0
 */
class NoPagingSniff extends AbstractArrayAssignmentRestrictionsSniff {

	/**
	 * Groups of variables to restrict.
	 *
	 * @return array
	 */
	public function getGroups() {
		return [
			'nopaging' => [
				'type' => 'error',
				'keys' => [
					'nopaging',
				],
			],
		];
	}

	/**
	 * Callback to process each confirmed key, to check value.
	 *
	 * @param  string $key   Array index / key.
	 * @param  mixed  $val   Assigned value.
	 * @param  int    $line  Token line.
	 * @param  array  $group Group definition.
	 * @return mixed         FALSE if no match, TRUE if matches, STRING if matches
	 *                       with custom error message passed to ->process().
	 */
	public function callback( $key, $val, $line, $group ) {
		$key = strtolower( $key );

		if ( $key === 'nopaging' && ( $val === 'true' || $val === 1 ) ) {
			return 'Disabling pagination is prohibited in VIP context, do not set `%s` to `%s` ever.';
		}

		return false;
	}

}

<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace WordPressVIPMinimum\Sniffs\Performance;

use PHPCSUtils\Utils\TextStrings;
use WordPressCS\WordPress\AbstractArrayAssignmentRestrictionsSniff;

/**
 * Flag disabling pagination via `nopaging` or `posts_per_page`/`numberposts` set to `-1`.
 *
 * @link https://docs.wpvip.com/technical-references/code-review/#no-limit-queries
 */
class NoPagingSniff extends AbstractArrayAssignmentRestrictionsSniff {

	/**
	 * Groups of variables to restrict.
	 *
	 * @return array<string, array<string, string|array<string>>>
	 */
	public function getGroups() {
		return [
			'nopaging' => [
				'type'    => 'error',
				'message' => 'Disabling pagination is prohibited in VIP context, do not set `%s` to `%s`.',
				'keys'    => [
					'nopaging',
				],
			],
			'posts_per_page' => [
				'type'    => 'error',
				'message' => 'Setting `%s` to `%s` disables pagination and is prohibited in VIP context.',
				'keys'    => [
					'posts_per_page',
					'numberposts',
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
	 *
	 * @return bool FALSE if no match, TRUE if matches.
	 */
	public function callback( $key, $val, $line, $group ) {
		$key = strtolower( $key );

		if ( $key === 'nopaging' ) {
			return ( $val === 'true' || $val === '1' );
		}

		// posts_per_page / numberposts: flag -1 (no limit).
		$val = TextStrings::stripQuotes( $val );
		return ( $val === '-1' );
	}
}

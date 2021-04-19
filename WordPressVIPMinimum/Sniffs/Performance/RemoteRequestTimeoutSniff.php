<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Performance;

use WordPressCS\WordPress\AbstractArrayAssignmentRestrictionsSniff;

/**
 * Flag REGEXP and NOT REGEXP in meta compare
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class RemoteRequestTimeoutSniff extends AbstractArrayAssignmentRestrictionsSniff {

	/**
	 * Groups of variables to restrict.
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
			'timeout' => [
				'type' => 'error',
				'keys' => [
					'timeout',
				],
			],
		];
	}

	/**
	 * Callback to process each confirmed key, to check value.
	 * This must be extended to add the logic to check assignment value.
	 *
	 * @param  string $key   Array index / key.
	 * @param  mixed  $val   Assigned value.
	 * @param  int    $line  Token line.
	 * @param  array  $group Group definition.
	 * @return mixed         FALSE if no match, TRUE if matches, STRING if matches
	 *                       with custom error message passed to ->process().
	 */
	public function callback( $key, $val, $line, $group ) {
		if ( (int) $val > 3 ) {
			return 'Detected high remote request timeout. `%s` is set to `%d`.';
		}
	}

}

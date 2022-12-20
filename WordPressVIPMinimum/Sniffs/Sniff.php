<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace WordPressVIPMinimum\Sniffs;

/**
 * Represents a WordPress\Sniff for sniffing VIP coding standards.
 *
 * Provides a bootstrap for the sniffs, to reduce code duplication.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
abstract class Sniff extends \WordPressCS\WordPress\Sniff {
	/**
	 * Merge a pre-set array with a ruleset-provided array.
	 *
	 * - By default flips custom lists to allow for using `isset()` instead
	 *   of `in_array()`.
	 * - When `$flip` is true:
	 *   * Presumes the base array is in a `'value' => true` format.
	 *   * Any custom items will be given the value `false` to be able to
	 *     distinguish them from pre-set (base array) values.
	 *   * Will filter previously added custom items out from the base array
	 *     before merging/returning to allow for resetting to the base array.
	 *
	 * {@internal Function is static as it doesn't use any of the properties or others
	 * methods anyway.}
	 *
	 * @param array $custom Custom list as provided via a ruleset.
	 * @param array $base   Optional. Base list. Defaults to an empty array.
	 *                      Expects `value => true` format when `$flip` is true.
	 * @param bool  $flip   Optional. Whether or not to flip the custom list.
	 *                      Defaults to true.
	 * @return array
	 */
	protected static function merge_custom_array( $custom, $base = array(), $flip = true ) {
		if ( true === $flip ) {
			$base = array_filter( $base );
		}

		if ( empty( $custom ) || ! \is_array( $custom ) ) {
			return $base;
		}

		if ( true === $flip ) {
			$custom = array_fill_keys( $custom, false );
		}

		if ( empty( $base ) ) {
			return $custom;
		}

		return array_merge( $base, $custom );
	}
}

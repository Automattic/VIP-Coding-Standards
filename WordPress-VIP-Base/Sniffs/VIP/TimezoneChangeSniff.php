<?php
/**
 * WordPress Coding Standard.
 *
 * @package WPCS\WordPressCodingStandards
 * @link    https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace Automattic\phpcs\WordPressVIP\Sniffs\VIP;

use WordPress\AbstractFunctionRestrictionsSniff;

/**
 * Disallow the changing of timezone.
 *
 * @link    https://vip.wordpress.com/documentation/use-current_time-not-date_default_timezone_set/
 *
 * @package WPCS\WordPressCodingStandards
 *
 * @since   0.3.0
 * @since   0.11.0 Extends the WordPress_AbstractFunctionRestrictionsSniff instead of the
 *                 Generic_Sniffs_PHP_ForbiddenFunctionsSniff.
 * @since   0.13.0 Class name changed: this class is now namespaced.
 *
 * @deprecated 1.0.0  This sniff has been moved to the `WP` category.
 *                    This file remains for now to prevent BC breaks.
 */
class TimezoneChangeSniff extends \WordPress\Sniffs\WP\TimezoneChangeSniff {

	/**
	 * Keep track of whether the warnings have been thrown to prevent
	 * the messages being thrown for every token triggering the sniff.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $thrown = array(
		'DeprecatedSniff'                 => false,
		'FoundPropertyForDeprecatedSniff' => false,
	);

	/**
	 * Don't use.
	 *
	 * @deprecated 1.0.0
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return void|int
	 */
	public function process_token( $stackPtr ) {
		if ( false === $this->thrown['DeprecatedSniff'] ) {
			$this->phpcsFile->addWarning(
				'The "WordPress.VIP.TimezoneChange" sniff has been renamed to "WordPress.WP.TimezoneChange". Please update your custom ruleset.',
				0,
				'DeprecatedSniff'
			);

			$this->thrown['DeprecatedSniff'] = true;
		}

		if ( ! empty( $this->exclude )
			&& false === $this->thrown['FoundPropertyForDeprecatedSniff']
		) {
			$this->phpcsFile->addWarning(
				'The "WordPress.VIP.TimezoneChange" sniff has been renamed to "WordPress.WP.TimezoneChange". Please update your custom ruleset.',
				0,
				'FoundPropertyForDeprecatedSniff'
			);

			$this->thrown['FoundPropertyForDeprecatedSniff'] = true;
		}

		return parent::process_token( $stackPtr );
	}

} // End class.

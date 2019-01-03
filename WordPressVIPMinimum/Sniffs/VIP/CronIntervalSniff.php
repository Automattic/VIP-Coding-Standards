<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace WordPressVIPMinimum\Sniffs\VIP;

use WordPress\Sniff;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * Flag cron schedules less than 15 minutes.
 *
 * @link https://vip.wordpress.com/documentation/vip-go/code-review-blockers-warnings-notices/#cron-schedules-less-than-15-minutes-or-expensive-events
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since   0.5.0
 */
class CronIntervalSniff extends \WordPress\Sniffs\WP\CronIntervalSniff {

	/**
	 * Minimum allowed cron interval in seconds.
	 *
	 * Defaults to 900 (= 15 minutes), which is the requirement for the VIP platform.
	 *
	 * @since 0.14.0
	 *
	 * @var int
	 */
	public $min_interval = 900;

}

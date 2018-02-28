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
 * Discourages the use of session functions.
 *
 * @link    https://vip.wordpress.com/documentation/vip/code-review-what-we-look-for/#session_start-and-other-session-related-functions
 *
 * @package WPCS\WordPressCodingStandards
 *
 * @since   0.3.0
 * @since   0.11.0 Extends the WordPress_AbstractFunctionRestrictionsSniff instead of the
 *                 Generic_Sniffs_PHP_ForbiddenFunctionsSniff.
 * @since   0.13.0 Class name changed: this class is now namespaced.
 */
class SessionFunctionsUsageSniff extends AbstractFunctionRestrictionsSniff {

	/**
	 * Groups of functions to restrict.
	 *
	 * Example: groups => array(
	 *  'lambda' => array(
	 *      'type'      => 'error' | 'warning',
	 *      'message'   => 'Use anonymous functions instead please!',
	 *      'functions' => array( 'file_get_contents', 'create_function' ),
	 *  )
	 * )
	 *
	 * @return array
	 */
	public function getGroups() {
		return array(
			'session' => array(
				'type'      => 'error',
				'message'   => 'The use of PHP session function %s() is prohibited.',
				'functions' => array(
					'session_abort',
					'session_cache_expire',
					'session_cache_limiter',
					'session_commit',
					'session_create_id',
					'session_decode',
					'session_destroy',
					'session_encode',
					'session_gc',
					'session_get_cookie_params',
					'session_id',
					'session_is_registered',
					'session_module_name',
					'session_name',
					'session_regenerate_id',
					'session_register_shutdown',
					'session_register',
					'session_reset',
					'session_save_path',
					'session_set_cookie_params',
					'session_set_save_handler',
					'session_start',
					'session_status',
					'session_unregister',
					'session_unset',
					'session_write_close',
				),
			),
		);
	} // End getGroups().

} // End class.

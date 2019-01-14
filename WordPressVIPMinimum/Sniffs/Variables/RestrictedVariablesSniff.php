<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace WordPressVIPMinimum\Sniffs\Variables;

use WordPressVIPMinimum\Sniffs\AbstractVariableRestrictionsSniff;

/**
 * Restricts usage of some variables in VIP context.
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since   0.5.0
 */
class RestrictedVariablesSniff extends AbstractVariableRestrictionsSniff {

	/**
	 * Groups of variables to restrict.
	 *
	 * Example: groups => array(
	 *  'wpdb' => array(
	 *      'type'          => 'error' | 'warning',
	 *      'message'       => 'Dont use this one please!',
	 *      'variables'     => array( '$val', '$var' ),
	 *      'object_vars'   => array( '$foo->bar', .. ),
	 *      'array_members' => array( '$foo['bar']', .. ),
	 *  )
	 * )
	 *
	 * @return array
	 */
	public function getGroups() {
		return [
			// @link https://lobby.vip.wordpress.com/wordpress-com-documentation/code-review-what-we-look-for/#wp_users-and-user_meta
			'user_meta' => [
				'type'        => 'error',
				'message'     => 'Usage of users/usermeta tables is highly discouraged in VIP context, For storing user additional user metadata, you should look at User Attributes.',
				'object_vars' => [
					'$wpdb->users',
					'$wpdb->usermeta',
				],
			],
			'session' => [
				'type'      => 'error',
				'message'   => 'Usage of $_SESSION variable is prohibited.',
				'variables' => [
					'$_SESSION',
				],
			],

			// @link https://lobby.vip.wordpress.com/wordpress-com-documentation/code-review-what-we-look-for/#caching-constraints
			'cache_constraints' => [
				'type'          => 'warning',
				'message'       => 'Due to using Batcache, server side based client related logic will not work, use JS instead.',
				'variables'     => [
					'$_COOKIE',
				],
				'array_members' => [
					'$_SERVER[\'HTTP_USER_AGENT\']',
					'$_SERVER[\'REMOTE_ADDR\']',
				],
			],
		];
	}

}

<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\VIP;

/**
 * Restricts usage of some functions in VIP context.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class RestrictedFunctionsSniff extends \WordPress\Sniffs\VIP\RestrictedFunctionsSniff {

	/**
	 * Groups of functions to restrict.
	 *
	 * @return array
	 */
	public function getGroups() {

		$original_groups = parent::getGroups();

		$new_groups = array(
			'wp_cache_get_multi'       => array(
				'type'      => 'error',
				'message'   => '`%s` is not supported on the WordPress.com VIP platform.',
				'functions' => array( 'wp_cache_get_multi' ),
			),
			'get_super_admins'         => array(
				'type'      => 'error',
				'message'   => '`%s` is prohibited on the WordPress.com VIP platform',
				'functions' => array( 'get_super_admins' ),
			),
			'internal'                 => array(
				'type'      => 'error',
				'message'   => '`%1$s()` is for internal use only.',
				'functions' => array(
					'wpcom_vip_irc',
				),
			),
			'rewrite_rules'            => array(
				'type'      => 'error',
				'message'   => '`%s` should not be used in any normal circumstances in the theme code.',
				'functions' => array(
					'flush_rewrite_rules',
				),
			),
			'attachment_url_to_postid' => array(
				'type'      => 'error',
				'message'   => '`%s()` is prohibited, please use `wpcom_vip_attachment_url_to_postid()` instead.',
				'functions' => array(
					'attachment_url_to_postid',
				),
			),
			'strip_tags'               => array(
				'type'      => 'error',
				'message'   => '`%s()` does not strip CSS and JS in between the script and style tags. `wp_strip_all_tags()` should be used instead.',
				'functions' => array(
					'strip_tags',
				),
			),
			'wp_mail'                  => array(
				'type'      => 'warning',
				'message'   => '`%s` should be used sparingly. For any bulk emailing should be handled by a 3rd party service, in order to prevent domain or IP addresses being flagged as spam.',
				'functions' => array(
					'wp_mail',
					'mail',
				),
			),
			'dbDelta'                  => array(
				'type'      => 'error',
				'message'   => 'All database modifications have to approved by the WordPress.com VIP team.',
				'functions' => array(
					'dbDelta',
				),
			),
			'is_multi_author'          => array(
				'type'      => 'warning',
				'message'   => '`%s` can be very slow on large sites and likely not needed on many VIP sites since they tend to have more than one author.',
				'functions' => array(
					'is_multi_author',
				),
			),
		);

		$deprecated_vip_helpers = array(
			'get_term_link'        => 'wpcom_vip_get_term_link',
			'get_term_by'          => 'wpcom_vip_get_term_by',
			'get_category_by_slug' => 'wpcom_vip_get_category_by_slug',
		);
		foreach ( $deprecated_vip_helpers as $restricted => $helper ) {
			$new_groups[ $helper ] = array(
				'type'      => 'warning',
				'message'   => "`%s()` is deprecated, please use {$restricted} instead.",
				'functions' => array(
					$helper,
				),
			);
			unset( $original_groups[ $restricted ] );
		}

		unset( $original_groups['file_get_contents'] );

		return array_merge( $original_groups, $new_groups );

	} // end getGroups().
}

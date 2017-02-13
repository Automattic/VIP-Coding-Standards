<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 */

/**
 * Restricts usage of some functions in VIP context.
 *
 */
class WordPressVIPMinimum_Sniffs_VIP_RestrictedFunctionsSniff extends WordPress_Sniffs_VIP_RestrictedFunctionsSniff {

	/**
	 * Groups of functions to restrict.
	 *
	 * @return array
	 */
	public function getGroups() {

		$original_groups = parent::getGroups();

		$new_groups = array(
			'wp_cache_get_multi' => array(
				'type' => 'error',
				'message' => '%s is not supported on the WordPress.com VIP platform.',
				'functions' => array( 'wp_cache_get_multi' ),
			),
			'get_super_admins' => array(
				'type' => 'error',
				'message' => '%s is prohibited on the WordPress.com VIP platform',
				'functions' => array( 'get_super_admins' ),
			),
			'get_children' => array(
				'type' => 'error',
				'message' => '%1$s() performs a no-LIMIT query by default, make sure to set a reasonable posts_per_page. %1$s() will do a -1 query by default, a maximum of 100 should be used.',
				'functions' => array(
					'get_children',
				),
				'internal' => array(
					'type' => 'error',
					'message' => '%1$s() is for internal use only.',
					'functions' => array(
						'wpcom_vip_irc',
						'wpcom_vip_irc_color',
					),
				),
			),
			'rewrite_rules' => array(
				'type' => 'error',
				'message' => '%s should not be used in any normal circumstances in the theme code.',
				'functions' => array(
					'flush_rewrite_rules',
				)
			),
		);

		$original_groups['get_posts']['functions'] = array_filter( $original_groups['get_posts']['functions'], function( $v ) {
			return ! in_array( $v, array( 'get_children' ), true );
		} );

		return array_merge( $original_groups, $new_groups );

	} // end getGroups().
}

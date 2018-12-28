<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\VIP;

use WordPress\AbstractFunctionRestrictionsSniff;
use WordPress\AbstractFunctionRestrictionSniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Restricts usage of some functions in VIP context.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class RestrictedFunctionsSniff extends AbstractFunctionRestrictionsSniff {

	/**
	 * Groups of functions to restrict.
	 *
	 * @return array
	 */
	public function getGroups() {

		$groups = [
			'wp_cache_get_multi' => [
				'type'      => 'error',
				'message'   => '`%s` is not supported on the WordPress.com VIP platform.',
				'functions' => [
					'wp_cache_get_multi',
				],
			],
			'opcache' => [
				'type'      => 'error',
				'message'   => '`%s` is prohibited on the WordPress VIP platform due to memory corruption.',
				'functions' => [
					'opcache_reset',
					'opcache_invalidate',
					'opcache_compile_file',
				],
			],
			'config_settings' => [
				'type'      => 'error',
				'message'   => '`%s` is not recommended for use on the WordPress VIP platform due to potential setting changes.',
				'functions' => [
					'opcache_​is_​script_​cached',
					'opcache_​get_​status',
					'opcache_​get_​configuration',
				],
			],
			'get_super_admins' => [
				'type'      => 'error',
				'message'   => '`%s` is prohibited on the WordPress.com VIP platform',
				'functions' => [
					'get_super_admins',
				],
			],
			'internal' => [
				'type'      => 'error',
				'message'   => '`%1$s()` is for internal use only.',
				'functions' => [
					'wpcom_vip_irc',
				],
			],
			// @link WordPress.com: https://lobby.vip.wordpress.com/wordpress-com-documentation/code-review-what-we-look-for/#flush_rewrite_rules
			'flush_rewrite_rules' => [
				'type'      => 'error',
				'message'   => '`%s` should not be used in any normal circumstances in the theme code.',
				'functions' => [
					'flush_rewrite_rules',
				],
			],
			'flush_rules' => [
				'type'      => 'error',
				'message'   => '`%s` should not be used in any normal circumstances in the theme code.',
				'functions' => [
					'flush_rules',
				],
				'class'     => [
					'$wp_rewrite' => true,
				],
			],
			'attachment_url_to_postid' => [
				'type'      => 'error',
				'message'   => '`%s()` is prohibited, please use `wpcom_vip_attachment_url_to_postid()` instead.',
				'functions' => [
					'attachment_url_to_postid',
				],
			],
			'dbDelta' => [
				'type'      => 'error',
				'message'   => 'All database modifications have to approved by the WordPress.com VIP team.',
				'functions' => [
					'dbDelta',
				],
			],
			// @link WordPress.com: https://vip.wordpress.com/documentation/vip/code-review-what-we-look-for/#switch_to_blog
			// @link VIP Go: https://vip.wordpress.com/documentation/vip-go/code-review-blockers-warnings-notices/#switch_to_blog
			'switch_to_blog' => [
				'type'      => 'error',
				'message'   => '%s() is not something you should ever need to do in a VIP theme context. Instead use an API (XML-RPC, REST) to interact with other sites if needed.',
				'functions' => [ 'switch_to_blog',
				],
			],
			'get_page_by_title' => [
				'type'      => 'error',
				'message'   => '%s() is prohibited, please use wpcom_vip_get_page_by_title() instead.',
				'functions' => [
					'get_page_by_title',
				],
			],
			'url_to_postid' => [
				'type'      => 'error',
				'message'   => '%s() is prohibited, please use wpcom_vip_url_to_postid() instead.',
				'functions' => [
					'url_to_postid',
					'url_to_post_id',
				],
			],
			// @link WordPress.com: https://lobby.vip.wordpress.com/wordpress-com-documentation/code-review-what-we-look-for/#custom-roles
			// @link VIP Go: https://vip.wordpress.com/documentation/vip-go/code-review-blockers-warnings-notices/#custom-roles
			'custom_role' => [
				'type'      => 'error',
				'message'   => 'Use wpcom_vip_add_role() instead of %s()',
				'functions' => [
					'add_role',
				],
			],
			// @link WordPress.com: https://lobby.vip.wordpress.com/wordpress-com-documentation/code-review-what-we-look-for/#wp_users-and-user_meta
			'user_meta' => [
				'type'      => 'error',
				'message'   => '%s() usage is highly discouraged on WordPress.com VIP due to it being a multisite, please see https://lobby.vip.wordpress.com/wordpress-com-documentation/code-review-what-we-look-for/#wp_users-and-user_meta.',
				'functions' => [
					'get_user_meta',
					'update_user_meta',
					'delete_user_meta',
					'add_user_meta',
				],
			],
			'term_exists' => [
				'type'      => 'error',
				'message'   => '%s() is highly discouraged due to not being cached; please use wpcom_vip_term_exists() instead.',
				'functions' => [
					'term_exists',
				],
			],
			'count_user_posts' => [
				'type'      => 'error',
				'message'   => '%s() is highly discouraged due to not being cached; please use wpcom_vip_count_user_posts() instead.',
				'functions' => [
					'count_user_posts',
				],
			],
			'wp_old_slug_redirect' => [
				'type'      => 'error',
				'message'   => '%s() is highly discouraged due to not being cached; please use wpcom_vip_old_slug_redirect() instead.',
				'functions' => [
					'wp_old_slug_redirect',
				],
			],
			'get_adjacent_post' => [
				'type'      => 'error',
				'message'   => '%s() is highly discouraged due to not being cached; please use wpcom_vip_get_adjacent_post() instead.',
				'functions' => [
					'get_adjacent_post',
					'get_previous_post',
					'get_previous_post_link',
					'get_next_post',
					'get_next_post_link',
				],
			],
			'get_intermediate_image_sizes' => [
				'type'      => 'error',
				'message'   => 'Intermediate images do not exist on the VIP platform, and thus get_intermediate_image_sizes() returns an empty array() on the platform. This behavior is intentional to prevent WordPress from generating multiple thumbnails when images are uploaded.',
				'functions' => [
					'get_intermediate_image_sizes',
				],
			],
			// @link WordPress.com: https://lobby.vip.wordpress.com/wordpress-com-documentation/code-review-what-we-look-for/#mobile-detection
			// @link VIP Go: https://vip.wordpress.com/documentation/vip-go/code-review-blockers-warnings-notices/#mobile-detection
			'wp_is_mobile' => [
				'type'      => 'error',
				'message'   => '%s() found. When targeting mobile visitors, jetpack_is_mobile() should be used instead of wp_is_mobile. It is more robust and works better with full page caching.',
				'functions' => [
					'wp_is_mobile',
				],
			],
			'wp_mail' => [
				'type'      => 'warning',
				'message'   => '`%s` should be used sparingly. For any bulk emailing should be handled by a 3rd party service, in order to prevent domain or IP addresses being flagged as spam.',
				'functions' => [
					'wp_mail',
					'mail',
				],
			],
			'is_multi_author' => [
				'type'      => 'warning',
				'message'   => '`%s` can be very slow on large sites and likely not needed on many VIP sites since they tend to have more than one author.',
				'functions' => [
					'is_multi_author',
				],
			],
			'advanced_custom_fields' => [
				'type'      => 'warning',
				'message'   => '`%1$s` does not escape output by default, please echo and escape with the `get_*()` variant function instead (i.e. `get_field()`).',
				'functions' => [
					'the_sub_field',
					'the_field',
				],
			],
			// @link WordPress.com: https://lobby.vip.wordpress.com/wordpress-com-documentation/code-review-what-we-look-for/#remote-calls
			// @link VIP Go: https://vip.wordpress.com/documentation/vip-go/code-review-blockers-warnings-notices/#remote-calls
			'wp_remote_get' => [
				'type'      => 'warning',
				'message'   => '%s() is highly discouraged, please use vip_safe_wp_remote_get() instead.',
				'functions' => [
					'wp_remote_get',
				],
			],
			// @link WordPress.com: https://lobby.vip.wordpress.com/wordpress-com-documentation/code-review-what-we-look-for/#custom-roles
			// @link VIP Go: https://vip.wordpress.com/documentation/vip-go/code-review-blockers-warnings-notices/#cache-constraints
			'cookies' => [
				'type'      => 'warning',
				'message'   => 'Due to using Batcache, server side based client related logic will not work, use JS instead.',
				'functions' => [
					'setcookie',
				],
			],
			// @todo Introduce a sniff specific to get_posts() that checks for suppress_filters=>false being supplied.
			'get_posts' => [
				'type'      => 'warning',
				'message'   => '%s() is uncached unless the "suppress_filters" parameter is set to false. If the suppress_filter parameter is set to false this can be safely ignored. More Info: https://vip.wordpress.com/documentation/vip-go/uncached-functions/',
				'functions' => [
					'get_posts',
					'wp_get_recent_posts',
					'get_children',
				],
			],
		];

		$deprecated_vip_helpers = [
			'get_term_link'        => 'wpcom_vip_get_term_link',
			'get_term_by'          => 'wpcom_vip_get_term_by',
			'get_category_by_slug' => 'wpcom_vip_get_category_by_slug',
		];
		foreach ( $deprecated_vip_helpers as $restricted => $helper ) {
			$groups[ $helper ] = [
				'type'      => 'warning',
				'message'   => "`%s()` is deprecated, please use `{$restricted}()` instead.",
				'functions' => [
					$helper,
				],
			];
		}

		return $groups;
	}

	/**
	 * Verify the current token is a function call.
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return bool
	 */
	public function is_targetted_token( $stackPtr ) {
		// Exclude function definitions, class methods, and namespaced calls.
		if ( \T_STRING === $this->tokens[ $stackPtr ]['code'] && isset( $this->tokens[ ( $stackPtr - 1 ) ] ) ) {
			$prev = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, ( $stackPtr - 1 ), null, true );
			if ( false !== $prev ) {
				// Check to see if function is part of specific classes.
				if ( ! empty( $this->groups[ $this->tokens[ $stackPtr ]['content'] ]['class'] ) ) {
					$prevPrev = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, ( $stackPtr - 2 ), null, true );
					if ( \T_OBJECT_OPERATOR === $this->tokens[ $prev ]['code'] && isset( $this->groups[ $this->tokens[ $stackPtr ]['content'] ]['class'][ $this->tokens[ $prevPrev ]['content'] ] ) ) {
						return true;
					} else {
						return false;
					}
				}
				// Skip sniffing if calling a same-named method, or on function definitions.
				$skipped = [
					\T_FUNCTION        => \T_FUNCTION,
					\T_CLASS           => \T_CLASS,
					\T_AS              => \T_AS, // Use declaration alias.
					\T_DOUBLE_COLON    => \T_DOUBLE_COLON,
					\T_OBJECT_OPERATOR => \T_OBJECT_OPERATOR,
				];
				if ( isset( $skipped[ $this->tokens[ $prev ]['code'] ] ) ) {
					return false;
				}
				// Skip namespaced functions, ie: \foo\bar() not \bar().
				if ( \T_NS_SEPARATOR === $this->tokens[ $prev ]['code'] ) {
					$pprev = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, ( $prev - 1 ), null, true );
					if ( false !== $pprev && \T_STRING === $this->tokens[ $pprev ]['code'] ) {
						return false;
					}
				}
			}
			return true;
		}
		return false;
	}
}

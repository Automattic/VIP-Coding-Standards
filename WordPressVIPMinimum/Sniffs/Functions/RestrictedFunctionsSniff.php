<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Functions;

use WordPressCS\WordPress\AbstractFunctionRestrictionsSniff;
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
				'message'   => '`%s` is prohibited on the WordPress.com VIP platform.',
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
				'type'       => 'error',
				'message'    => '`%s` should not be used in any normal circumstances in the theme code.',
				'functions'  => [
					'flush_rules',
				],
				'object_var' => [
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
			// @link VIP Go: https://wpvip.com/documentation/vip-go/code-review-blockers-warnings-notices/#switch_to_blog
			'switch_to_blog' => [
				'type'      => 'error',
				'message'   => '%s() is not something you should ever need to do in a VIP theme context. Instead use an API (XML-RPC, REST) to interact with other sites if needed.',
				'functions' => [
					'switch_to_blog',
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
				],
			],
			// @link WordPress.com: https://lobby.vip.wordpress.com/wordpress-com-documentation/code-review-what-we-look-for/#custom-roles
			// @link VIP Go: https://wpvip.com/documentation/vip-go/code-review-blockers-warnings-notices/#custom-roles
			'custom_role' => [
				'type'      => 'error',
				'message'   => 'Use wpcom_vip_add_role() instead of %s().',
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
			// @link VIP Go: https://wpvip.com/documentation/vip-go/code-review-blockers-warnings-notices/#mobile-detection
			'wp_is_mobile' => [
				'type'      => 'error',
				'message'   => '%s() found. When targeting mobile visitors, jetpack_is_mobile() should be used instead of wp_is_mobile. It is more robust and works better with full page caching.',
				'functions' => [
					'wp_is_mobile',
				],
			],
			'session' => [
				'type'      => 'error',
				'message'   => 'The use of PHP session function %s() is prohibited.',
				'functions' => [
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
				],
			],
			'file_ops' => [
				'type'      => 'error',
				'message'   => 'Filesystem writes are forbidden, please do not use %s().',
				'functions' => [
					'delete',
					'file_put_contents',
					'flock',
					'fputcsv',
					'fputs',
					'fwrite',
					'ftruncate',
					'is_writable',
					'is_writeable',
					'link',
					'rename',
					'symlink',
					'tempnam',
					'touch',
					'unlink',
				],
			],
			'directory' => [
				'type'      => 'error',
				'message'   => 'Filesystem writes are forbidden, please do not use %s().',
				'functions' => [
					'mkdir',
					'rmdir',
				],
			],
			'chmod' => [
				'type'      => 'error',
				'message'   => 'Filesystem writes are forbidden, please do not use %s().',
				'functions' => [
					'chgrp',
					'chown',
					'chmod',
					'lchgrp',
					'lchown',
				],
			],
			'site_option' => [
				'type'      => 'error',
				'message'   => '%s() will overwrite network option values, please use the `*_option()` equivalent instead (e.g. `update_option()`).',
				'functions' => [
					'add_site_option',
					'update_site_option',
					'delete_site_option',
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
			// @link VIP Go: https://wpvip.com/documentation/vip-go/code-review-blockers-warnings-notices/#remote-calls
			'wp_remote_get' => [
				'type'      => 'warning',
				'message'   => '%s() is highly discouraged, please use vip_safe_wp_remote_get() instead.',
				'functions' => [
					'wp_remote_get',
				],
			],
			// @link WordPress.com: https://lobby.vip.wordpress.com/wordpress-com-documentation/code-review-what-we-look-for/#custom-roles
			// @link VIP Go: https://wpvip.com/documentation/vip-go/code-review-blockers-warnings-notices/#cache-constraints
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
				'message'   => '%s() is uncached unless the "suppress_filters" parameter is set to false. If the suppress_filter parameter is set to false this can be safely ignored. More Info: https://wpvip.com/documentation/vip-go/uncached-functions/.',
				'functions' => [
					'get_posts',
					'wp_get_recent_posts',
					'get_children',
				],
			],
			'create_function' => [
				'type'      => 'warning',
				'message'   => '%s() is highly discouraged, as it can execute arbritary code (additionally, it\'s deprecated as of PHP 7.2): https://wpvip.com/documentation/vip-go/code-review-blockers-warnings-notices/#eval-and-create_function. )',
				'functions' => [
					'create_function',
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
	 * Verify the current token is a function call or a method call on a specific object variable.
	 *
	 * This differs to the parent class method that it overrides, by also checking to see if the
	 * function call is actually a method call on a specific object variable. This works best with global objects,
	 * such as the `flush_rules()` method on the `$wp_rewrite` object.
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return bool
	 */
	public function is_targetted_token( $stackPtr ) {
		// Exclude function definitions, class methods, and namespaced calls.
		if ( \T_STRING === $this->tokens[ $stackPtr ]['code'] && isset( $this->tokens[ $stackPtr - 1 ] ) ) {
			// Check if this is really a function.
			$next = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true );
			if ( false !== $next && T_OPEN_PARENTHESIS !== $this->tokens[ $next ]['code'] ) {
				return false;
			}

			$prev = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $stackPtr - 1, null, true );
			if ( false !== $prev ) {

				// Start difference to parent class method.
				// Check to see if function is a method on a specific object variable.
				if ( ! empty( $this->groups[ $this->tokens[ $stackPtr ]['content'] ]['object_var'] ) ) {
					$prevPrev = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $stackPtr - 2, null, true );

					return \T_OBJECT_OPERATOR === $this->tokens[ $prev ]['code'] && isset( $this->groups[ $this->tokens[ $stackPtr ]['content'] ]['object_var'][ $this->tokens[ $prevPrev ]['content'] ] );
				} // End difference to parent class method.

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
					$pprev = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $prev - 1, null, true );
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

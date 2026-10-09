<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Functions;

use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\Arrays;
use PHPCSUtils\Utils\GetTokensAsString;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\TextStrings;
use WordPressCS\WordPress\AbstractFunctionRestrictionsSniff;

/**
 * Restricts usage of some functions in VIP context.
 */
class RestrictedFunctionsSniff extends AbstractFunctionRestrictionsSniff {

	/**
	 * Groups of functions to restrict.
	 *
	 * @return array<string, array<string, string|array<string>|array<string, bool>>>
	 */
	public function getGroups() {

		$groups = [
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
					'opcache_is_script_cached',
					'opcache_get_status',
					'opcache_get_configuration',
				],
			],
			'internal' => [
				'type'      => 'error',
				'message'   => '`%1$s()` is for internal use only.',
				'functions' => [
					'wpcom_vip_irc',
				],
			],
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
			// @link https://docs.wpvip.com/technical-references/code-review/vip-notices/#h-switch_to_blog
			'switch_to_blog' => [
				'type'      => 'warning',
				'message'   => '%s() may not work as expected since it only changes the database context for the blog and does not load the plugins or theme of that site. Filters or hooks on the blog you are switching to will not run.',
				'functions' => [
					'switch_to_blog',
				],
			],
			'url_to_postid' => [
				'type'      => 'error',
				'message'   => '%s() is prohibited, please use wpcom_vip_url_to_postid() instead.',
				'functions' => [
					'url_to_postid',
				],
			],
			// @link https://docs.wpvip.com/how-tos/customize-user-roles/
			'custom_role' => [
				'type'      => 'error',
				'message'   => 'Use wpcom_vip_add_role() instead of %s().',
				'functions' => [
					'add_role',
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
			// @link https://docs.wpvip.com/technical-references/code-review/vip-warnings/#h-mobile-detection
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
			'stats_get_csv' => [
				'type'      => 'error',
				'message'   => 'Using `%s` outside of Jetpack context pollutes the stats_cache entry in the wp_options table. We recommend building a custom function instead.',
				'functions' => [
					'stats_get_csv',
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
			// @link https://docs.wpvip.com/technical-references/code-review/vip-warnings/#h-remote-calls
			'wp_remote_get' => [
				'type'      => 'warning',
				'message'   => '%s() is highly discouraged. Please use vip_safe_wp_remote_get() instead which is designed to more gracefully handle failure than wp_remote_get() does.',
				'functions' => [
					'wp_remote_get',
				],
			],
			// @link https://docs.wpvip.com/technical-references/code-review/vip-errors/#h-cache-constraints
			'cookies' => [
				'type'      => 'error',
				'message'   => 'Due to server-side caching, server-side based client related logic might not work. We recommend implementing client side logic in JavaScript instead.',
				'functions' => [
					'setcookie',
				],
			],
			'get_posts' => [
				'type'      => 'warning',
				'message'   => '%s() is uncached unless the "suppress_filters" parameter is set to false. If the parameter is set to false in a way this sniff cannot detect, such as via a variable, this can be safely ignored. More Info: https://docs.wpvip.com/technical-references/caching/uncached-functions/.',
				'functions' => [
					'get_posts',
					'wp_get_recent_posts',
					'get_children',
				],
			],
			'create_function' => [
				'type'      => 'warning',
				'message'   => '%s() is highly discouraged, as it can execute arbritary code (additionally, it\'s deprecated as of PHP 7.2): https://docs.wpvip.com/technical-references/code-review/vip-warnings/#h-eval-and-create_function. )',
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
		if ( empty( $this->groups[ $this->tokens[ $stackPtr ]['content'] ]['object_var'] ) ) {
			return parent::is_targetted_token( $stackPtr );
		}

		// Start difference to parent class method.
		// Check to see if the token is a method call on a specific object variable.
		$next = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true );
		if ( $next === false || $this->tokens[ $next ]['code'] !== T_OPEN_PARENTHESIS ) {
			return false;
		}

		$prev = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $stackPtr - 1, null, true );
		if ( $this->tokens[ $prev ]['code'] !== T_OBJECT_OPERATOR
			&& $this->tokens[ $prev ]['code'] !== T_NULLSAFE_OBJECT_OPERATOR
		) {
			return false;
		}

		$prevPrev = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $prev - 1, null, true );

		return $this->tokens[ $prevPrev ]['code'] === T_VARIABLE
			&& isset( $this->groups[ $this->tokens[ $stackPtr ]['content'] ]['object_var'][ $this->tokens[ $prevPrev ]['content'] ] );
	}

	/**
	 * Process a matched token.
	 *
	 * This differs to the parent class method that it overrides, by not flagging calls to
	 * `get_posts()` and `wp_get_recent_posts()` which set `suppress_filters` to `false`.
	 * `get_children()` is still flagged, as it also performs a no-LIMIT query by default.
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param string $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (function name) which was matched
	 *                                in lowercase.
	 *
	 * @return void
	 */
	public function process_matched_token( $stackPtr, $group_name, $matched_content ) {
		if ( $group_name === 'get_posts'
			&& $matched_content !== 'get_children'
			&& $this->sets_suppress_filters_to_false( $stackPtr )
		) {
			return;
		}

		parent::process_matched_token( $stackPtr, $group_name, $matched_content );
	}

	/**
	 * Check whether a function call passes an array of arguments which sets `suppress_filters` to `false`.
	 *
	 * @param int $stackPtr The position of the function call name in the stack.
	 *
	 * @return bool
	 */
	private function sets_suppress_filters_to_false( $stackPtr ) {
		$args_param = PassedParameters::getParameter( $this->phpcsFile, $stackPtr, 1, 'args' );
		if ( $args_param === false ) {
			return false;
		}

		$array_ptr = $this->phpcsFile->findNext( Tokens::$emptyTokens, $args_param['start'], $args_param['end'] + 1, true );
		if ( $array_ptr === false ) {
			return false;
		}

		// The array has to be the whole argument.
		$open_close = Arrays::getOpenClose( $this->phpcsFile, $array_ptr );
		if ( $open_close === false
			|| $open_close['closer'] !== $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $args_param['end'], $args_param['start'], true )
		) {
			return false;
		}

		$is_false = false;
		foreach ( PassedParameters::getParameters( $this->phpcsFile, $array_ptr ) as $item ) {
			$arrow = Arrays::getDoubleArrowPtr( $this->phpcsFile, $item['start'], $item['end'] );
			if ( $arrow === false ) {
				$first = $this->phpcsFile->findNext( Tokens::$emptyTokens, $item['start'], $item['end'] + 1, true );
				if ( $first !== false && $this->tokens[ $first ]['code'] === T_ELLIPSIS ) {
					// An unpacked array can override an earlier key.
					$is_false = false;
				}
				continue;
			}

			$key = TextStrings::stripQuotes( GetTokensAsString::noEmpties( $this->phpcsFile, $item['start'], $arrow - 1 ) );
			if ( $key === 'suppress_filters' ) {
				// WP_Query only checks the value is falsy, so a lone `false` or `0` both count.
				$value    = $this->phpcsFile->findNext( Tokens::$emptyTokens, $arrow + 1, $item['end'] + 1, true );
				$is_false = $value !== false
					&& $this->phpcsFile->findNext( Tokens::$emptyTokens, $value + 1, $item['end'] + 1, true ) === false
					&& ( $this->tokens[ $value ]['code'] === T_FALSE
						|| ( $this->tokens[ $value ]['code'] === T_LNUMBER && $this->tokens[ $value ]['content'] === '0' ) );
			}
		}

		return $is_false;
	}
}

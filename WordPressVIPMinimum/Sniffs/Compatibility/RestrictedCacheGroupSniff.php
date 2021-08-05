<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Compatibility;

use WordPressCS\WordPress\AbstractFunctionParameterSniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Check if cache group is persisted by wp-memcached to avoid cache value corruption.
 *
 * Note: wp-memcached is automatically enabled on WordPress VIP.
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since 2.4.0
 */
class RestrictedCacheGroupSniff extends AbstractFunctionParameterSniff {

	/**
	 * The group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'wp_cache_functions';

	/**
	 * Functions that can assign or modify cache key.
	 *
	 * @var array <string function name> => <bool (true)>
	 */
	protected $target_functions = [
		'wp_cache_add'     => true,
		'wp_cache_set'     => true,
		'wp_cache_replace' => true,
	];

	/**
	 * Tokens acceptable within third parameter.
	 *
	 * @var array
	 */
	private $safe_tokens = [];

	/**
	 * List of global cache groups persisted by wp-memcached.
	 *
	 * @link https://github.com/Automattic/wp-memcached/blob/master/readme.txt
	 *
	 * @var array
	 */
	private $wp_memcached_groups = [
		'blog-details'              => true,
		'blog-id-cache'             => true,
		'blog-lookup'               => true,
		'bookmark'                  => true,
		'calendar'                  => true,
		'category'                  => true,
		'category_relationships'    => true,
		'comment'                   => true,
		'counts'                    => true,
		'general'                   => true,
		'global-posts'              => true,
		'options'                   => true,
		'plugins'                   => true,
		'post_ancestors'            => true,
		'post_format_relationships' => true,
		'post_meta'                 => true,
		'post_tag_relationships'    => true,
		'posts'                     => true,
		'rss'                       => true,
		'site-lookup'               => true,
		'site-options'              => true,
		'site-transient'            => true,
		'term_meta'                 => true,
		'terms'                     => true,
		'themes'                    => true,
		'timeinfo'                  => true,
		'transient'                 => true,
		'user_meta'                 => true,
		'useremail'                 => true,
		'userlogins'                => true,
		'usermeta'                  => true,
		'users'                     => true,
		'userslugs'                 => true,
		'widget'                    => true,

	];

	/**
	 * Process the parameters of a matched function.
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param array  $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (function name) which was matched.
	 * @param array  $parameters      Array with information about the parameters.
	 * @return int|void Integer stack pointer to skip forward or void to continue
	 *                  normal file processing.
	 */
	public function process_parameters( $stackPtr, $group_name, $matched_content, $parameters ) {
		if ( ! isset( $parameters[3] ) ) {
			return; // Bail, less than 3 parameters.
		}

		if ( $this->safe_tokens === [] ) {
			$this->safe_tokens = Tokens::$textStringTokens + Tokens::$heredocTokens + Tokens::$emptyTokens;
			unset( $this->safe_tokens[ T_DOUBLE_QUOTED_STRING ], $this->safe_tokens[ T_INLINE_HTML ] );
		}

		$scope_start = $parameters[3]['start'];
		$scope_end   = $parameters[3]['end'] + 1;

		$nonTextStringToken = $this->phpcsFile->findNext(
			$this->safe_tokens,
			$scope_start,
			$scope_end,
			true
		);

		if ( $nonTextStringToken !== false ) {
			return; // Bail, indeterminable.
		}

		$textString = $this->phpcsFile->findNext(
			Tokens::$textStringTokens,
			$scope_start,
			$scope_end,
			false
		);

		$content = $this->tokens[ $textString ]['content'];

		if ( $this->tokens[ $textString ]['code'] === T_CONSTANT_ENCAPSED_STRING ) {
			$content = $this->strip_quotes( $content );
		} else {
			$content = rtrim( $content, "\n\r" );
		}

		if ( isset( $this->wp_memcached_groups[ $content ] ) ) {
			$msg = 'Please do not use or override the global cache group name "%s", as it is being persisted by wp-memcached: https://docs.wpvip.com/technical-references/caching/object-cache/.';
			$this->phpcsFile->addError(
				$msg,
				$textString,
				'MemcachedGroupNameFound',
				[ $content ]
			);
		}
	}
}

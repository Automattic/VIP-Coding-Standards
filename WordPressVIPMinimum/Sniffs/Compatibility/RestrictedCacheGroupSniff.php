<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Compatibility;

use WordPressCS\WordPress\AbstractFunctionParameterSniff;

/**
 * This sniff checks if global memcached group names are being used.
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since 2.3.0
 */
class RestrictedCacheGroupSniff extends AbstractFunctionParameterSniff {

	/**
	 * The group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'wp_cache_functions';

	/**
	 * Functions this sniff is looking for.
	 *
	 * @var array The only requirement for this array is that the top level
	 *            array keys are the names of the functions you're looking for.
	 *            Other than that, the array can have arbitrary content
	 *            depending on your needs.
	 */
	protected $target_functions = [
		'wp_cache_set'         => true,
		'wp_cache_add'         => true,
	];

	/**
	 * List of cache group names already in use by wp-memcached.
	 *
	 * @var array
	 */
	private $wp_memcached_groups = [
		'category_relationships'    => true,
		'post_format_relationships' => true,
		'post_tag_relationships'    => true,
		'term_meta'                 => true,
		'user_meta'                 => true,
		'blog-details'              => true,
		'blog-id-cache'             => true,
		'blog-lookup'               => true,
		'bookmark'                  => true,
		'calendar'                  => true,
		'category'                  => true,
		'comment'                   => true,
		'counts'                    => true,
		'general'                   => true,
		'global-posts'              => true,
		'options'                   => true,
		'plugins'                   => true,
		'post_ancestors'            => true,
		'post_meta'                 => true,
		'posts'                     => true,
		'rss'                       => true,
		'site-lookup'               => true,
		'site-options'              => true,
		'site-transient'            => true,
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
		if ( count( $parameters ) > 2 && isset( $this->wp_memcached_groups[ trim( $parameters[3]['raw'], '"\'' ) ] ) ) {
			$this->phpcsFile->addError( 'Please do not use cache group %s, as it is already in use by wp-memcached: https://docs.wpvip.com/technical-references/caching/object-cache/.', $stackPtr, 'wp_memcached', $parameters[3]['raw'] );
		}
	}
}

<?php



// Error
wp_cache_set( 'cache_key', $data, 'users', 10 * MINUTE_IN_SECONDS );
wp_cache_set( 'cache_key', $data, "userlogins" );
wp_cache_add( $cache_key, 'data', 'posts' );
wp_cache_add( $cache_key,   $data,   "category_relationships", 600000 );
wp_cache_add(
	$key,
	$data,
	'usermeta'
);
wp_cache_set( 'key',
// Random comment to throw things off but not really.

[ 1, 2, 3 ], 'userslugs'
);
wp_cache_set( $key, [ 1, 2, 3 ],        'post_meta' );
wp_cache_add($cache, "1", 'themes');

wp_cache_add(
    $cache_key, // Key.
    'data', // Data.
    'posts' // Group name.
);

wp_cache_add( $cache_key, 'data', <<<EOD
posts
EOD
);

wp_cache_add( $cache_key, 'data', <<<'EOD'
posts
EOD
);

\wp_cache_set( 'cache_key', $data, "userlogins" );

// OK
$key = 'cache_key';
wp_cache_set( $key, $data, 'users_stuff' );
wp_cache_add( $key, 'data', "posts_" );
wp_cache_set( $key, $data, 'custom_taxonomy_relationships' );
wp_cache_add( $key, 'post_meta', 'group' );
wp_cache_add( $key, $data, posts );  // Indeterminable.
wp_cache_add( $key, $data, $name ); // Indeterminable.
wp_cache_add( $key, $data, 'category' . '_relationships' ); // Indeterminable.
wp_cache_add( $key, $data, "category_$relationships" ); // Indeterminable.
namespace\wp_cache_set( 'cache_key', $data, "userlogins" ); // Not native WP function.
MyNamespace\wp_cache_set( 'cache_key', $data, "userlogins" );
$myOwnObj->wp_cache_set( 'cache_key', $data, "userlogins" );
ClassName::wp_cache_set( 'cache_key', $data, "userlogins" );

<?php

$data = [ 'foo', 'bar' ];

// Error
wp_cache_set( 'cache_key', $data, 'users', 10 * MINUTE_IN_SECONDS );
wp_cache_set( 'cache_key', $data, "userlogins" );
wp_cache_add( $cache_key, 'data', 'posts' );
wp_cache_add( $cache_key,   $data,   "category_relationships", 600000 );

// OK
$key = 'cache_key';
wp_cache_set( $key, $data, 'users_stuff' );
wp_cache_add( $key, 'data', "posts_" );
wp_cache_set( $key, $data, 'custom_taxonomy_relationships' );
<?php

// Bad code start.
$bad_wp_users = wp_cache_get( md5( self::CACHE_KEY . '_wp_users'), self::CACHE_GROUP );
$bad_wp_users = false; // Bad.

if ( empty( $bad_wp_users ) ) {
	$bad_wp_users = get_users();
}

// Good code start.
$good_wp_users = wp_cache_get( md5( self::CACHE_KEY . '_wp_users'), self::CACHE_GROUP );

if ( empty( $good_wp_users ) ) {
	$good_wp_users = false;
	$good_wp_users = get_users();
}
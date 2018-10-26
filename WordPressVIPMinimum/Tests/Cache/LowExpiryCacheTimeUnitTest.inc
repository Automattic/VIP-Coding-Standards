<?php

$data = [ 'test', 'banana', 'ice cream', '123' ];
$testing = 'test_key';
$group = 'test_group';

// Ok.
wp_cache_set( 'test', $data, $group, 300 );
wp_cache_set( $testing, $data, 'test_group', 5*MINUTE_IN_SECONDS );
wp_cache_set( 123, $data, 'test_group',  5 * MINUTE_IN_SECONDS );
wp_cache_set( 1234, $data, '', 425 );
wp_cache_set( $testing, $data, null, 350 );
wp_cache_set( $testing, $data );
wp_cache_set( 'test', $data, $group );

wp_cache_add( 'test', $data, $group, 300 );
wp_cache_add( $testing, $data, 'test_group', 6*MINUTE_IN_SECONDS );
wp_cache_add( 1234, $data, '', 425 );
wp_cache_add( $testing, $data, null, 350 );

wp_cache_replace( 'test', $data, $group, 300 );
wp_cache_replace(  $testing,  $data,  'test_group',   8*MINUTE_IN_SECONDS );
wp_cache_replace( 1234, $data, '', 425 );
wp_cache_replace( $testing, $data, null, 350 );

// Bad.
wp_cache_set( 'test', $data, $group, 100 ); // Lower than 300.
wp_cache_set( 'test', $data, $group,  2*MINUTE_IN_SECONDS  ); // Lower than 300.
wp_cache_set( 123, $data, null, 1.5 * MINUTE_IN_SECONDS ); // Lower than 300.
wp_cache_set( $testing, $data, '', 1.5 * MINUTE_IN_SECONDS ); // Lower than 300.

wp_cache_add( 'test', $data, $group, 100 ); // Lower than 300.
wp_cache_add( 'test', $data, $group,  2*MINUTE_IN_SECONDS  ); // Lower than 300.
wp_cache_add( 123, $data, null, 1.5 * MINUTE_IN_SECONDS ); // Lower than 300.
wp_cache_add( $testing, $data, '', 1.5 * MINUTE_IN_SECONDS ); // Lower than 300.

wp_cache_replace( 'test', $data, $group, 100 ); // Lower than 300.
wp_cache_replace( 'test', $data, $group,  2*MINUTE_IN_SECONDS  ); // Lower than 300.
wp_cache_replace( 123, $data, null, 1.5 * MINUTE_IN_SECONDS ); // Lower than 300.
wp_cache_replace( $testing, $data, '', 1.5 * MINUTE_IN_SECONDS ); // Lower than 300.

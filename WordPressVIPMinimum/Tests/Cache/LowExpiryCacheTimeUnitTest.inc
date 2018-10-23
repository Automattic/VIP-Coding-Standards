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

// Bad.
wp_cache_set( 'test', $data, $group, 100 ); // Lower than 300.
wp_cache_set( 'test', $data, $group,  2*MINUTE_IN_SECONDS  ); // Lower than 300.
wp_cache_set( 123, $data, null, 1.5 * MINUTE_IN_SECONDS ); // Lower than 300.
wp_cache_set( $testing, $data, '', 1.5 * MINUTE_IN_SECONDS ); // Lower than 300.

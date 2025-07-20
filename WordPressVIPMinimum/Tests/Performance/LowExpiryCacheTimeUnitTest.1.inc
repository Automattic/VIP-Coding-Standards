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
\wp_cache_set( $testing, $data );
wp_cache_set( 'test', $data, $group );

wp_cache_add( 'test', $data, $group, 300 );
wp_cache_add( $testing, $data, 'test_group', 6*MINUTE_IN_SECONDS );
WP_CACHE_ADD( 1234, $data, '', 425 );
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
\wp_cache_add( 'test', $data, $group,  2*MINUTE_IN_SECONDS  ); // Lower than 300.
wp_cache_add( 123, $data, null, 1.5 * MINUTE_IN_SECONDS ); // Lower than 300.
wp_cache_add( $testing, $data, '', 1.5 * MINUTE_IN_SECONDS ); // Lower than 300.

wp_cache_replace( 'test', $data, $group, 100 ); // Lower than 300.
wp_cache_replace( 'test', $data, $group,  2*MINUTE_IN_SECONDS  ); // Lower than 300.
WP_CACHE_REPLACE( 123, $data, null, 1.5 * MINUTE_IN_SECONDS ); // Lower than 300.
wp_cache_replace( $testing, $data, '', 1.5 * MINUTE_IN_SECONDS ); // Lower than 300.

// Test error being reported on the line containing the parameter.
wp_cache_replace(
	$testing,
	$data,
	'',
	1.5 * MINUTE_IN_SECONDS  // Lower than 300.
);

// Test calculations with floats.
wp_cache_replace( $testing, $data, '', 7.5 * MINUTE_IN_SECONDS ); // OK.
wp_cache_replace( $testing, $data, '', 500 * 0.1, ); // Bad.

// Test comment handling.
wp_cache_add( 'test', $data, $group, /* Deliberately left empty */ ); // OK.
wp_cache_add( 'test', $data, $group, 600 * 0.1 /* = 1 minute */ ); // Bad.
wp_cache_add(
	'test',
	$data,
	$group,
	// Cache for 10 minutes.
	600
); // OK.

wp_cache_add(
	'test',
	$data,
	$group,
	// phpcs:ignore Stnd.Cat.Sniff -- Just here for testing purposes.
	600
); // OK.

// Test variable/constant with or without calculation being passed.
WP_Cache_Set( $key, $data, '', $time ); // Manual inspection warning.
wp_cache_set( $key, $data, '', PREFIX_FIVE_MINUTES ); // Manual inspection warning.
wp_cache_set( $key, $data, '', 20 * $time ); // Manual inspection warning.
wp_cache_set( $key, $data, '', $base + $extra, ); // Manual inspection warning.
\wp_cache_set( $key, $data, '', 300 + $extra ); // Manual inspection warning.
wp_cache_set( $key, $data, '', PREFIX_CUSTOM_TIME * 5); // Manual inspection warning.

// Test calculations with additional aritmetic operators.
wp_cache_replace( 'test', $data, $group, +5 ** MINUTE_IN_SECONDS ); // OK.
wp_cache_add( 'test', $data, $group, WEEK_IN_SECONDS / 3 + HOUR_IN_SECONDS  ); // OK.

// Test calculations grouped with parentheses.
wp_cache_set( $key, $data, '', (24 * 60 * 60) ); // OK.
wp_cache_set( $key, $data, '', (-(2 * 60) + 600), ); // OK.
wp_cache_set( $key, $data, '', (2 * 60) ); // Bad.


// Test handling of numbers passed as strings.
wp_cache_set( 'test', $data, $group, '300' ); // OK - type cast to integer within the function.
wp_cache_set( 'test', $data, $group, '100' * 3 ); // OK - type cast to integer by PHP during the calculation.
wp_cache_set( 'test', $data, $group, '-10' ); // Bad - type cast to integer within the function.
wp_cache_replace( $testing, $data, '', '1.5' * MINUTE_IN_SECONDS ); // Bad - type cast to integer by PHP during the calculation.

// Test handling of 0 values. `0` is the default value for the parameter and translates internally to "no expiration".
wp_cache_add( 'test', $data, $group, 0 ); // OK.
wp_cache_add( 'test', $data, $group, 0.0 ); // OK.
wp_cache_add( 'test', $data, $group, '0' ); // OK.
wp_cache_add( 'test', $data, $group, false ); // OK.
wp_cache_add( 'test', $data, $group, null ); // OK.

// Test handling of other scalar values.
wp_cache_add( 'test', $data, $group, true ); // Bad - becomes integer 1.

// Test passing just and only one of the time constants, including passing it as an FQN.
wp_cache_set( 'test', $data, $group, HOUR_IN_SECONDS ); // OK.
wp_cache_set( 'test', $data, $group, \MONTH_IN_SECONDS ); // OK.

// Test passing something which may look like one of the time constants, but isn't.
wp_cache_set( 'test', $data, $group, month_in_seconds ); // Bad - constants are case-sensitive.
wp_cache_set( 'test', $data, $group, HOUR_IN_SECONDS::methodName() ); // Bad - not a constant.
wp_cache_set( 'test', $data, $group, $obj->MONTH_IN_SECONDS ); // Bad - not a constant.
wp_cache_set( 'test', $data, $group, $obj::MONTH_IN_SECONDS ); // Bad - not the WP constant.
WP_Cache_Set( 'test', $data, $group, PluginNamespace\SubLevel\DAY_IN_SECONDS, ); // Bad - not the WP constant.

// Test passing negative number as cache time.
wp_cache_set( 'test', $data, $group, -300 ); // Bad.
wp_cache_add( $testing, $data, 'test_group', -6 * MINUTE_IN_SECONDS ); // Bad.

// Test more complex logic in the parameter.
wp_cache_add( $key, $data, '', ($toggle ? 200 : 400) ); // Manual inspection warning.

// Test handling of non-numeric data in text string.
wp_cache_set( 'test', $data, $group, '' ); // Manual inspection warning.
wp_cache_set( 'test', $data, $group, '300 Mulberry Street' ); // Manual inspection warning.

// Test handling of edge case/parse error.
wp_cache_set( 'test', $data, $group,\); // OK, ignore.

// Test handling of some modern PHP syntaxes.
wp_cache_add('test', $data, $group, ...$params); // PHP 5.6 argument unpacking. Manual inspection warning.
wp_cache_add( $key, $data, '', $toggle ?? 400) ); // PHP 7.0 null coalesce. Manual inspection warning.
add_action('my_action', wp_cache_set(...)); // PHP 8.1 first class callable. OK, ignore.

// Looks like a function call, but is a PHP 8.0+ class instantiation via an attribute.
#[WP_Cache_Replace('text', 'data', 'group', 50)]
function foo() {}

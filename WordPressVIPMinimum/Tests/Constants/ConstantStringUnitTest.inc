<?php

if ( ! defined( 'WPCOM_VIP' ) ) { // Okay.
	define( 'WPCOM_VIP', true ); // Okay.
}

if ( ! defined( WPCOM_VIP ) ) { // Error.
	define( WPCOM_VIP, true ); // Error.
}

namespace Foo\Bar;
const REST_ALLOWED_META_PREFIXES = [ 'foo-', 'bar-', 'baz-' ];
if ( defined( __NAMESPACE__ . '\REST_ALLOWED_META_PREFIXES' ) && in_array( 'foo-', REST_ALLOWED_META_PREFIXES, true ) ) { // Ok.
	define( __NAMESPACE__ . '\\' . REST_ALLOWED_META_PREFIXES[1], $value ); // OK.
}

define( __NAMESPACE__ . '\PLUGIN_URL', \plugins_url( '/', __FILE__ ) ); // OK.
if ( defined( __NAMESPACE__ . '\\LOADED' ) ) {} // OK.

if ( defined( $obj->constant_name_property ) === false ) { // OK.
   define( $variable_containing_constant_name, $constant_value ); // OK.
}

if ( defined( MY_PREFIX . '_CONSTANT_NAME' ) === false ) { // OK.
   define( 'PREFIX_' . $variable_part, $constant_value ); // OK.
}

if ( ! defined($generator->get()) { // OK.
	define( $generator->getLast(), 'value'); // OK.
}

$defined = defined(); // OK, ignore.
$defined = defined( /*comment*/ ); // OK, ignore.

// Safeguard that calls to namespaced functions or methods are not confused with the global functions.
if ( ! Not\defined( WPCOM_VIP ) ) { // Okay.
	$obj->define( WPCOM_VIP, true ); // Okay.
}

if ( ! \Fully\Qualified\defined( WPCOM_VIP ) ) { // Okay.
	namespace\define( WPCOM_VIP, true ); // Okay.
}

// Safeguard that fully qualified function calls are handled correctly.
if ( ! \defined( 'WPCOM_VIP' ) ) { // Okay.
	\define( 'WPCOM_VIP', true, ); // Okay.
}

if ( ! \defined( WPCOM_VIP, ) ) { // Error.
	\define( WPCOM_VIP, true ); // Error.
}

// Safeguard correct handling of case-insensitivity of function names.
if ( ! DEFINED( WPCOM_VIP ) ) { // Error.
	Define( WPCOM_VIP, true ); // Error.
}

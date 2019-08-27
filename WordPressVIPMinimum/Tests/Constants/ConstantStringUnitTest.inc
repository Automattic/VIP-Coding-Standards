<?php

if ( ! defined( 'WPCOM_VIP' ) ) { // Okay.
	define( 'WPCOM_VIP', true ); // Okay.
}

if ( ! defined( WPCOM_VIP ) ) { // Error.
	define( WPCOM_VIP ); // Error.
}

namespace Foo/Bar;
const REST_ALLOWED_META_PREFIXES = [ 'foo-', 'bar-', 'baz-' ];
if ( defined( __NAMESPACE__ . '\REST_ALLOWED_META_PREFIXES' ) && in_array( 'foo-', REST_ALLOWED_META_PREFIXES, true ) ) { // Ok.
	define( __NAMESPACE__ . REST_ALLOWED_META_PREFIXES ); // Error.
}

<?php

if ( A8C_PROXIED_REQUEST === true ) { // Bad. Should produce a warning.

}

if ( defined( 'A8C_PROXIED_REQUEST' ) && true === constant( 'A8C_PROXIED_REQUEST' ) ) { // Bad. Should produce a warning.

}

define( 'A8C_PROXIED_REQUEST', false ); // Bad. Should never attempt to define this.

define( 'JETPACK_DEV_DEBUG', true ); // Bad. Should never attempt to define this.

define( 'WP_CRON_CONTROL_SECRET', true ); // Bad. Should never attempt to define this.

if ( defined( 'JETPACK_DEV_DEBUG' ) ) { // Okay. Can touch.

}

if ( constant( 'WP_CRON_CONTROL_SECRET' ) ) { // Okay. Can touch.

}

define( '"A8C_PROXIED_REQUEST"', false ); // Okay, well not really as the name is not a valid PHP constant name, but that's not our concern.

$name = DB_NAME; // Bad. The constant is null on the platform.

$name = constant( 'DB_NAME' ); // Bad. The constant is null on the platform.

if ( defined( 'DB_NAME' ) ) { // Okay. Checking whether a constant exists is not the same as using it.

}

$name = My_Class::DB_NAME; // Okay. Class constant.

$name = $wpdb->DB_NAME; // Okay. Object property.

$name = DB_NAME(); // Okay. Function call.

$name = my_function( 'DB_NAME' ); // Okay. Just a text string.

$name = [ 'DB_NAME' => 'my_database' ]; // Okay. Just a text string.

class My_Class {
	const DB_NAME = 'my_database'; // Okay. Class constant declaration.
}

$name = \DB_NAME; // Bad. Fully qualified reference to the global constant.

$name = My_Namespace\DB_NAME; // Okay. Namespaced constant which just shares the name.

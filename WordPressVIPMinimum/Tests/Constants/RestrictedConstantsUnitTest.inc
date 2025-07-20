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




/*
Needs tests with fully qualified function calls and fully qualified usage of global constant
Needs tests with redefinition via `const` keyword in global namespace
  -> also means tests are needed against false positives if the code is namespaced, i.e.
namespace Foo;
const JETPACK_DEV_DEBUG = true; // OK, not the global constant.

Needs test with class constant declaration of the same (to prevent false positives)


*/

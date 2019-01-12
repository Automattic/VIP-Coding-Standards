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
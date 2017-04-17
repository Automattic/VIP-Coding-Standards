<?php

if ( A8C_PROXIED_REQUEST === true ) { // Bad. Should produce a warning.

}

if ( defined( 'A8C_PROXIED_REQUEST' ) && true === constant( 'A8C_PROXIED_REQUEST' ) ) { // Bad. Should produce a warning.

}

define( 'A8C_PROXIED_REQUEST', false ); // Bad. Should never try to define this.
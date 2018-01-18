<?php

if ( ! defined( 'WPCOM_VIP' ) ) { // Okay.
	define( 'WPCOM_VIP', true ); // Okay.
}

if ( ! defined( WPCOM_VIP ) ) { // NOK.
	define( WPCOM_VIP ); // NOK.
}
<?php

wp_redirect( 'https://vip.wordpress.com' ); //Ok.

exit();

wp_safe_redirect( 'https.//vip.wordpress.com' ); // NOK.

wp_redirect( 'https://vip.wordpress.com' ); // NOK.

if ( wp_redirect( 'https://vip.wordpress.com' ) ) { //Ok.
	exit;
}
<?php

$a = time();
$b = time() * 2;

$wp_random_testing = create_function( '$a, $b', 'return ( $b / $a ); ');

echo esc_html( $wp_random_testing( $a, $b ) );

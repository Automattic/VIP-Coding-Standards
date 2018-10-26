<?php

$a = time();
$b = time() * 2;

/*
 * Test for true positive
 */
$wp_random_testing = create_function( '$a, $b', 'return ( $b / $a ); ');


/*
 * Test for false positives
 */

echo esc_html( $wp_random_testing( $a, $b ) );

class Foo {
    public create_function = 'foo';
}
$c = new Foo();
$c->create_function = 'bar' . ( 1 === 1 ?? 'foo' );

$m = bla_function( '$a, $b', 'return ( $b / $a ); ');

$wp_random_testing = 
   create_function2( '$a, $b', 'return ( $b / $a ); ');

$wp_random_testing = 
   create_function3( '$a, $b', 'return ( $b / $a ); ');


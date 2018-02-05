<?php

function my_test() {
	echo esc_html( "foo" );
}


$my_notokay_func = 'extract';
$my_notokay_func();

$my_okay_func = 'my_test';
$my_okay_func();




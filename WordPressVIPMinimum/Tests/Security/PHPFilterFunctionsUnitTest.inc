<?php

$url = 'http://www.google.ca';
$_GET['foo'] = 'bar';
$array = [ 'something_something', 'https://www.google.com', '6' ];

// Ok.
filter_var( $url, FILTER_SANITIZE_URL );
filter_var( 'test', FILTER_SANITIZE_STRING );
filter_var(  "test", FILTER_SANITIZE_STRING );
filter_input( INPUT_GET, 'foo', FILTER_SANITIZE_STRING );
filter_input( INPUT_GET,  "foo"   , FILTER_SANITIZE_STRING );
filter_var_array( $array, FILTER_SANITIZE_STRING );
filter_input_array( $array, FILTER_SANITIZE_STRING );
filter_input_array( $array,FILTER_SANITIZE_STRING      );

// Bad.
filter_input( INPUT_GET, 'foo' ); // Missing third parameter.
filter_input( INPUT_GET, 'foo', FILTER_DEFAULT ); // This filter ID does nothing.
filter_input( INPUT_GET, "foo", FILTER_UNSAFE_RAW  ); // This filter ID does nothing.
filter_var( $url ); // Missing second parameter.
filter_var( $url, FILTER_DEFAULT ); // This filter ID does nothing.
filter_var( 'https://google.com', FILTER_UNSAFE_RAW ); // This filter ID does nothing.
filter_var_array( $array ); // Missing second parameter.
filter_var_array( $array, FILTER_DEFAULT ); // This filter ID does nothing.
filter_var_array( $array, FILTER_UNSAFE_RAW ); // This filter ID does nothing.
filter_input_array( $array ); // Missing second parameter.
filter_input_array( $array, FILTER_DEFAULT ); // This filter ID does nothing.
filter_input_array( $array, FILTER_UNSAFE_RAW ); // This filter ID does nothing.

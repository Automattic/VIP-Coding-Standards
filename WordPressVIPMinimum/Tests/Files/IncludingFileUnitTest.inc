<?php

function custom_function( $file ) {
	return 'http://www.google.com/' . $file;
}

// Ok.
require_once __DIR__ . "/my_file.php";
include( __DIR__ . "/my_file.php" );
include( locate_template('index-loop.php') );
require dirname( __FILE__ ) . '/my_file.php' );
require dirname( __FILE__, 2 ) . '/my_file.php' );
require dirname( dirname( __FILE__ ) ) . '/my_file.php' );
require dirname( dirname( __DIR__ ) ) . '/my_file.php' );

// Warnings.
include ( MY_CONSTANT . "my_file.php" ); // Custom constant.
require_once ( MY_CONSTANT . "my_file.php" ); // Custom constant.
require_once( custom_function( 'test_file.php' ) ); // Custom function.
require custom_function /* comment */ ( 'test_file.php' ); // Custom function.

// Errors.
require_once "my_file.php"; // Not absolute path.
require '../my_file.php'; // Not absolute path.
require '../../my_file.php'; // Not absolute path.
include( 'http://www.google.com/bad_file.php' ); // External URL.
include_once("http://www.google.com/bad_file.php"); // External URL.
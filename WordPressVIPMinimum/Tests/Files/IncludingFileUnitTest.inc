<?php

function custom_function( $file ) {
	return 'http://www.google.com/' . $file;
}

// OK.
require_once __DIR__ . "/my_file.php";
include( __DIR__ . "/my_file.php" );
include( locate_template('index-loop.php') );
require dirname( __FILE__ ) . '/my_file.php' );
require dirname( __FILE__, 2 ) . '/my_file.php' );
require dirname( dirname( __FILE__ ) ) . '/my_file.php' );
require dirname( dirname( __DIR__ ) ) . '/my_file.php' );
require_once( get_parent_theme_file_path( '/my_file.php' ); );
include CUSTOM__DIR . 
'file.php';
include_once CUSTOMBASE . 'test.php';

// Warnings.
include ( MY_CONSTANT . "my_file.php" ); // Custom constant.
require_once ( MY_CONSTANT . "my_file.php" ); // Custom constant.
require_once( custom_function( 'test_file.php' ) ); // Custom function.
require custom_function /* comment */ ( 'test_file.php' ); // Custom function.
require_once(    $custom_path ); // Custom variable.

// Errors.
require_once "my_file.php"; // Not absolute path.
require '../my_file.php'; // Not absolute path.
require '../../my_file.php'; // Not absolute path.
include( 'http://www.google.com/bad_file.php' ); // External URL.
include_once("http://www.google.com/bad_file.php"); // External URL.
include( 'http://www.path.com/bad.php' ); // External URL but includes keyword from $customPaths.
require 'https://dir.com/file.php' ); // External URL but includes keyword from $customPaths.
include( TEMPLATEPATH . 'file.php' ); // Restricted constant.
require_once STYLESHEETPATH . "path.php"; // Restricted constant.

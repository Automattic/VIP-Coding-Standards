<?php

$query = "SELECT * FROM $wpdb->users"; // Error.

$wp_db->update( $wpdb->users, array( 'displayname' => 'Kanobe!' ), array( 'ID' => 1 ) ); // Error.

$db_name = $wpdb->dbname; // Error.
$query   = "SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$wpdb->dbname'"; // Error.
$db_name = $other_db->dbname; // Ok, not $wpdb.

$query = "SELECT * FROM $wpdb->posts"; // Ok.

if ( isset( $_SERVER['REMOTE_ADDR'] ) ) { // OK.
	foo( $_SERVER['REMOTE_ADDR'] ); // Warning.
}

$x = $_COOKIE['bar']; // Warning.

$y = $_SERVER['REQUEST_URI']; // Ok.

// Error.
$query = <<<EOD
SELECT * FROM $wpdb->users
EOD;

// Warning
$phrase = <<<EOD
Your user-agent is {$_SERVER['HTTP_USER_AGENT']}
EOD;

// phpcs:set WordPressVIPMinimum.Variables.RestrictedVariables exclude[] user_meta
$query = "SELECT * FROM $wpdb->users"; // Ok, excluded.

// phpcs:set WordPressVIPMinimum.Functions.RestrictedFunctions exclude[]

foo( $_SESSION ); // Error.
foo( $_SESSION['bar'] ); // Error.

if ( isset( $_SESSION ) ) { // OK.
	$cache = false;
}

<?php

/*
 * Not the sniff target.
 */
$otherVar = 'ignored';
$notSERVER['PHP_AUTH_USER'];
$_server['PHP_AUTH_USER']; // Variable names are case-sensitive.


/*
 * This is okay.
 */
var_dump($_SERVER); // No key access.
$_SERVER[] = 10;
$_SERVER['SOME_OTHER_INDEX'];
$_SERVER['php_auth_user']; // Array indices are case-sensitive.

// PHP 7.4+ spread in array.
$a = [...$_SERVER]; // No key access.

// Ignore as undetermined.
$_SERVER[ $name ];
$_SERVER["HTTP_X_{$a}"];
$_SERVER['HTTP_X_' . $a];


/*
 * Bad variables. Should never happen.
 */
$_SERVER['PHP_AUTH_USER'];
$_SERVER[  'PHP_AUTH_PW'  ];
$_SERVER  [
    'HTTP_X_IP_TRAIL'
];
$_SERVER /*comment*/ [ /*comment*/ 'HTTP_X_FORWARDED_FOR'  /*comment*/ ];
$_SERVER["REMOTE_ADDR"]; // Let's test one with double quotes too.


/*
 * Prevent various false positives.
 */
// These look like "forbidden" indices, but aren't.
$_SERVER['PHP_"AUTH"_PW'];
$_SERVER["PHP_'AUTH'_PW"];

// Sniff should not get confused over static OO property access.
class PeopleDoSillyThings extends AllowedByPHP {
	public static $_SERVER = [];

	public function show() {
		var_export(self::$_SERVER['PHP_AUTH_USER']);
		var_export(parent  ::  $_SERVER['PHP_AUTH_PW']);
		var_export(static::$_SERVER['HTTP_X_FORWARDED_FOR']);
		var_export(OtherClass::$_SERVER['HTTP_X_IP_TRAIL']);
	}
}

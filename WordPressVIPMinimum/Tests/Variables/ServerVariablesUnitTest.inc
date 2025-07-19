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

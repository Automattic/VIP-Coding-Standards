<?php

require_once __DIR__ . "/my_file.php"; // OK.

require_once "my_file.php"; // OK.

include( __DIR__ . "/my_file.php" ); // OK.

include ( MY_CONSTANT . "my_file.php" ); // OK.

require_once ( MY_CONSTANT . "my_file.php" ); // OK.

include( locate_template('index-loop.php') ); // OK.

require_once __DIR__ . "/my_file.svg"; // NOK.

require_once "my_file.svg"; // NOK.

include( __DIR__ . "/my_file.svg" ); // NOK.

include ( MY_CONSTANT . "my_file.svg" ); // NOK.

require_once ( MY_CONSTANT . "my_file.svg" ); // NOK.

include( locate_template('index-loop.svg') ); // NOK.

require_once __DIR__ . "/my_file.css"; // NOK.

require_once "my_file.css"; // NOK.

include( __DIR__ . "/my_file.css" ); // NOK.

include ( MY_CONSTANT . "my_file.css" ); // NOK.

require_once ( MY_CONSTANT . "my_file.css" ); // NOK.

include( locate_template('index-loop.css') ); // NOK.

require_once __DIR__ . "/my_file.csv"; // NOK.

require_once "my_file.inc"; // OK.

include( __DIR__ . "/my_file.csv" ); // NOK.

include ( MY_CONSTANT . "my_file.csv" ); // NOK.

require_once ( MY_CONSTANT . "my_file.csv" ); // NOK.

include( locate_template('index-loop.csv') ); // NOK.

echo file_get_contents( 'index-loop.svg' ); // XSS OK.

echo file_get_contents( 'index-loop.css' ); // XSS OK.
<?php

require_once __DIR__ . "/my_file.php"; // OK.

require "my_file.php"; // OK.

include_once( __DIR__ . "/my_file.php" ); // OK.

include ( MY_CONSTANT . "my_file.INC" ); // OK.

require_once ( MY_CONSTANT . "my_file.php" ); // OK.

Include( locate_template('index-loop.PHP') ); // OK.

require_once __DIR__ . "/my_file.SVG"; // NOK.

Require_Once "my_file.svg"; // NOK.

include( __DIR__ . "/my_file.svg" ); // NOK.

include ( MY_CONSTANT . "my_file.svg" ); // NOK.

require ( MY_CONSTANT . "my_file.svg" ); // NOK.

include( locate_template('index-loop.svg') ); // NOK.

require_once __DIR__ . "/my_file.CSS"; // NOK.

require_once "my_file.css"; // NOK.

include_once( __DIR__ . "/my_file.css" ); // NOK.

include ( MY_CONSTANT . "my_file.css" ); // NOK.

require_once ( MY_CONSTANT . "my_file.css" ); // NOK.

include( locate_template('index-loop.Css') ); // NOK.

REQUIRE_ONCE __DIR__ . "/my_file.csv"; // NOK.

require_once "my_file.inc"; // OK.

include( __DIR__ . "/my_file.CSV" ); // NOK.

include ( MY_CONSTANT . "my_file.csv" ); // NOK.

require_once ( MY_CONSTANT . "my_file.csv" ); // NOK.

include( locate_template('index-loop.csv') ); // NOK.

echo file_get_contents( 'index-loop.svg' ); // XSS OK.

echo file_get_contents( 'index-loop.css' ); // XSS OK.

include_once 'path/to/geoip.phar'; // OK.

require dirname(__DIR__) . '/externals/aws-sdk.phar'; // OK.

require "$path/$file.inc"; // OK.

require "$path/$file.css"; // NOK.

include_once $path . '/' . "$file.js"; // NOK.

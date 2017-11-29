<?php

require_once __DIR__ . "/my_file.php"; // OK.

require_once "my_file.php"; // NOK.

include( __DIR__ . "/my_file.php" ); // OK.

include ( MY_CONSTANT . "my_file.php" ); // Warning.

require_once ( MY_CONSTANT . "my_file.php" ); // Warning.

include( locate_template('index-loop.php') ); // Ok.
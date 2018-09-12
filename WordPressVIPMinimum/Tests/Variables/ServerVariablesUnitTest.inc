<?php

// Bad variables. Should never happen.
$_SERVER['PHP_AUTH_PW']; 
$_SERVER['HTTP_X_IP_TRAIL'];
$_SERVER['HTTP_X_FORWARDED_FOR'];  
$_SERVER["REMOTE_ADDR"]; 	// let's test one with double quotes too

$_SERVER['SOME_OTHER_VARIABLE']; // We don't care.
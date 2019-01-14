<?php

if ( isset( $_GET['utm_medium'] ) && 'email' === $_GET["utm_medium"] ) { // 2 warnings.

}

$hello = $_GET['migSource']; // Warning.

$hey = $_GET['ThisIsOkay']; // OK.
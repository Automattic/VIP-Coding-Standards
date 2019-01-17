<?php

function my_do_robotstxt() {
	echo 'Disallow: foo/bar2.txt';
}

add_action( 'do_robotstxt', 'my_do_robotstxt');

add_action( 'do_robotstxt', function() {
	echo 'Disallow: foo/bar.txt';
});

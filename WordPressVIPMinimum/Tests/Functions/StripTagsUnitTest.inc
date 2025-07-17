<?php

/*
 * Not the sniff target.
 */
use strip_tags;
use MyNs\{
	function strip_tags,
};

my\ns\strip_tags($a, $b);
$this->strip_tags($a, $b);
$this?->strip_tags($a, $b);
MyClass::strip_tags($a, $b);
echo STRIP_TAGS;
namespace\strip_tags($a, $b);

// Looks like a function call, but is a PHP 8.0+ class instantiation via an attribute.
#[Strip_tags('text')]
function foo() {}

strip_tag( 'Test', $html ); // Ok - similarly-named function.
wp_strip_all_tags( $string ); // Ok.


/*
 * These should all be flagged with a warning.
 */
strip_tags( 'Testing' ); // Warning.
STRIP_TAGS( 'Test', $html ); // Warning.
\strip_tags( 'Test' . ', ' . 'HTML', ); // Warning.
strip_tags( 'Test, String', $html, ); // Warning.
Strip_Tags( $string ); // Warning.

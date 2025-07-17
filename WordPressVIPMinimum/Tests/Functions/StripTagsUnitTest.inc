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

// The function should always be flagged, even during live coding (missing required parameter).
strip_tags();

strip_tags(...$params); // PHP 5.6 argument unpacking.

// Safeguard correct handling of function calls using PHP 8.0+ named parameters.
strip_tags(allowed_tags: $allowed, string: $html ); // Warning.
strip_tags(string: $html); // Warning.
strip_tags(allowed_tags: $allowed); // Warning. Invalid function call, but that's not the concern of this sniff.

\strip_tags(string: $html, allowed_tag: $allowed); // Warning (mind: deliberate typo in param name).
strip_tags(html: $html); // Warning (mind: deliberately using incorrect param name).

<?php

/*
 * Not the sniff target.
 */
use str_replace;

my\ns\str_replace('foo', 'bar', 'foobar');
$this->str_replace('foo', 'bar', 'foobar');
$this?->str_replace('foo', 'bar', 'foobar');
MyClass::str_replace('foo', 'bar', 'foobar');
echo STR_REPLACE;
namespace\str_replace('foo', 'bar', 'foobar', $count);

// Looks like a function call, but is a PHP 8.0+ class instantiation via an attribute.
#[Str_Replace('foo', 'bar', 'foobar')]
function foo() {}

// Ignore PHP 5.6 argument unpacking.
str_replace('foo', 'bar', ...$params);

// Ignore PHP 8.1 first class callable.
add_filter('my_filter', str_replace(...));

// Incomplete function calls, should be ignored by the sniff.
str_replace();
str_replace('foo', 'bar');


/*
 * These should all be okay.
 */
str_replace( $foo->getSearch(), 'bar', 'foobar' );
\str_replace( 'foo', $bar, 'foobar' );
str_replace( 'foo', 'bar', $foobar, );
STR_REPLACE( $foo, $bar, 'foobar' );
str_replace( 'foo', get_replacements(), $foobar, $count );

str_replace( array( 'foo', "$bar" ), $bar, 'foobar' );

str_replace( array( $foo, $bar ), array( 'bar', 'foo' ), array( 'foobar', 'foobar' ) );
\Str_Replace( [ 'foo', 'bar' ], array( $foo, SOME_CONSTANT ), 'foobar' );
str_replace( array( 'foo', "bar" ), [ 'bar', 'foo' ], $foobar, );
str_replace( [ $foo, $bar->prop ], /*comment*/ [ 'bar', 'foo' ], $foobar );


/*
 * These should all be flagged with a warning.
 */
str_replace( 'foo', 'bar', 'foobar' ); // Bad.
Str_replace(
	'foo', // Comment.
	'bar', /* comment */
	'foobar'
); // Bad.
str_replace( array( 'foo', 'bar' ), array( 'bar', 'foo' ), array( 'foobar', 'foobar' ) ); // Bad.
str_replace( 'foo', 'bar', 'foobar', $count ); // Bad.

// Handle PHP 5.4+ short array syntax correctly.
\str_replace( [ 'foo', 'bar' ], [ 'bar', 'foo' ], [ 'foobar', 'foobar' ], ); // Bad.

// Don't confuse PHP 7.1+ short list with short array.
str_replace( [ $a, $b ] = $search, [ 'bar', 'foo' ], 'foobar', ); // OK.

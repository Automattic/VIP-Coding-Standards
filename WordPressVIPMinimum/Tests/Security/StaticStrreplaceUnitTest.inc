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

// Safeguard support for PHP 8.0+ named parameters.
str_replace( search: [ 'foo', 'bar' ], replace: 'baz', count: $count ); // OK, well not really, missing required $subject param, but not our concern.
str_replace( search: 'foo', replace: 'baz', subjects: [ 'foobar', 'foobar' ] ); // OK, well not really, typo in $subject param name, not our concern.
str_replace( subject: $subject, count: $count, replace: 'baz', search: 'foo', ); // OK, different parameter order.

str_replace( count: $count, subject: [ 'foobar', 'foobar' ], search: 'foo', replace: 'baz', ); // Bad with different parameter order.
str_replace( [ 'foo', 'bar' ], 'baz', count: $count, subject: 'foobar' ); // Bad, with mixed named and unnamed params.

// Make sure some variations of how plain strings can be passed are handled.
str_replace( 'fo' . 'o', 'bar', 'foobar' ); // Bad.
str_replace( array( 'foo', 'bar' ) + array( 'baz', 'fop' ), array( 'bar', 'foo' ), 'foobar' ); // Bad.

// Safeguard correct handling of PHP 5.3+ nowdocs.
str_replace(
	'foo',
	'bar',
	<<<'EOD'
foobar
EOD
); // Bad.

// Safeguard correct handling of heredocs without interpolation.
str_replace( array(<<<EOD
foo
EOD
	),
	'bar',
	<<<"EOD"
foobar
EOD
); // Bad.

// Ensure heredocs with interpolation are ignored.
str_replace( 'foo', 'bar', <<<EOD
Some text and $foobar
EOD
); // OK.

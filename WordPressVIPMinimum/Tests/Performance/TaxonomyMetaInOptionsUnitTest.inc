<?php

/*
 * Not the sniff target.
 */
use function add_option;

my\ns\get_option( "taxonomy_rating_$tag_id" );
$this->delete_option( "taxonomy_rating_$tag_id" );
$this?->update_option( "taxonomy_rating_$tag_id" );
MyClass::get_option( "taxonomy_rating_$tag_id", );
echo DELETE_OPTION;
namespace\add_option( "taxonomy_rating_$tag_id" );

function &update_option() {}

/*
 * These should all be okay.
 */
// Incomplete function call, should be ignored by the sniff.
$incorrect_but_ok = get_option();
$incorrect_but_ok = update_option();

// Ignore as undetermined.
Get_Option( $option );
\add_option( $obj->get_optionname() );
update_option( OPTION_NAME, );
\DELETE_OPTION( MyClass::$option_name );

get_option(...$params); // PHP 5.6 argument unpacking, parameter value unknown.

// Looks like a function call, but is a PHP 8.0+ class instantiation via an attribute.
#[Get_Option('taxonomy_rating_')]
function foo() {}

array_walk($options, \update_option(...),); // PHP 8.1 first class callable.

// Ignore as not touching taxonomy term patterns.
\get_option( 'option_name' );
add_option( 'option_' . $post_id, );
update_Option( "prefix_{$setting}" );
add_option( 'option_' . MY_CONSTANT, );
add_option( 'foo_' . $obj->$propname );
update_option( 'foo_' . $obj->page_id ); // NOK.


/*
 * These should all be flagged.
 */
get_option( "taxonomy_rating_$obj->term_id", ); // NOK.
\delete_option( "taxonomy_rating_{$obj->term_id}" ); // NOK.
get_option( /*comment*/ "taxonomy_rating_$tag_id" ); // NOK.
UpDate_OPTION( 'taxonomy_rating_' . $category_id /*comment*/ , ); // NOK.
\Get_Option( "taxonomy_rating_{$cat_id}" ); // NOK.
add_option( 'taxonomy_rating_' . $obj->term_id ); // NOK.


// Safeguard handling of function calls using PHP 8.0+ named parameters.
get_option(default_value: false); // OK, well, not really, missing required $option param, but that's not the concern of this sniff.
add_option(value: "$tag_id", option: 'option_name'); // OK.
update_option(autoload: true, optin: "$tag_id", value: 10,); // OK, well, not really, typo in param name, but that's not the concern of the sniff.

add_option(value: $value, option: "$tag_id" ); // Bad.

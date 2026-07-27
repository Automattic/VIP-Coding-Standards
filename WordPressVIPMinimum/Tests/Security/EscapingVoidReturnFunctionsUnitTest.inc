<?php

/*
 * Not the sniff target.
 */
use function esc_attr;

my\ns\esc_html( _e( $something ) );
$this->esc_js( _deprecated_file() );
$this?->esc_textarea( _e( $something ) );
MyClass::esc_url( wp_die(), );
echo WP_KSES;
namespace\wp_kses_post( user_error( $something ) );

function esc_js( _E $param ) {} // Class "_E" as type declaration.

/*
 * These should all be okay.
 */
// Incomplete function call, should be ignored by the sniff.
$incorrect_but_ok = esc_html();
$incorrect_but_ok = wp_kses();

// Parameter does not contain a function call.
esc_html_x( $hook_name );
\wp_kses_data( $obj->_deprecated_function() );
esc_attr__( CONSTANT_NAME, );
\ESC_ATTR_X( "do_$something" );

esc_attr(...$params); // PHP 5.6 argument unpacking.

#[Esc_Textarea('text')] // PHP 8.0+ class instantiation via an attribute. Can't contain a nested function call anyway.
function foo() {}

array_walk($text_strings, \esc_html__(...),); // PHP 8.1 first class callable.

// Parameter contains a function call but not calling one of the printing functions.
esc_html( __( $something ) );
Esc_URL_Raw( \get( $url ) );
\esc_xml( deprecated_argument() ); // Note: missing "_" prefix for printing function.
wp_kses_one_attr   (
	/* comment */
	get_attr(),
);


/*
 * These should all be flagged.
 */
esc_html( _e( $something ) );
esc_attr__( \_deprecated_argument( $a ) );
ESC_ATTR_E( _Deprecated_Constructor($a), );
\esc_attr_x( _deprecated_file(), );
esc_attr( _deprecated_function() );
esc_HTML__( _deprecated_hook() );
esc_html_e( _Doing_It_Wrong( $a ) );
esc_html_X( \_e( $foo ), );
\esc_html( /*comment*/ _ex( $foo ) );
esc_js( printf( $foo ) );
Esc_textarea( trigger_error( $foo ) );
\esc_URL_raw( \user_Error( $foo, '' ), );
esc_url( vprintf( $foo, ) /*comment*/ );
WP_Kses_Data( WP_DIE( $foo ), );
wp_kses_one_attr /*comment*/ ( wp_dropdown_pages( $pages ) );
wp_kses_post(
	\_deprecated_function( $fn )
);
wp_kses( _ex() );

// Adding custom printing functions is supported.
// phpcs:set WordPressVIPMinimum.Security.EscapingVoidReturnFunctions customPrintingFunctions[] to_screen,my_print
esc_attr( to_screen( $var1 ) );
\wp_kses_post( my_print() );
// phpcs:set WordPressVIPMinimum.Security.EscapingVoidReturnFunctions customPrintingFunctions[]

tag_escape( $tag ); // OK.
tag_escape( _e() ); // Bad.

// Bug: these are not function calls inside.
esc_attr__( User_Error::CONSTANT_NAME, ); // OK.
esc_js( _doing_it_wrong::class ); // OK, PHP 5.5 ::class resolution.

// Safeguard handling of function calls using PHP 8.0+ named parameters.
esc_attr_x( context: get_context(), domain: _e(),); // OK, well, not really, missing required $text param, but that's not the concern of this sniff.
esc_url_raw(protocols: $protocols, url: $url); // OK.
wp_kses_one_attr(element: $element, att: trigger_error( $foo ) ); // OK, well, not really, typo in param name, but that's not the concern of the sniff.
wp_kses( content: \not_deprecated_function( $fn ) ); // OK.
wp_kses( content: /*comment*/ ); // OK, well, not really, invalid function call, but that's not the concern of the sniff.

esc_html_x(context: $c, text: _doing_it_wrong() ); // Bad.
wp_kses(allowed_html: $allowed_html, content: printf() ); // Bad.
esc_url(protocols: $protocols, url: vprintf()); // Bad.

// These are no longer flagged as they are not "escaping" functions for the purpose of this sniff.
esc_sql( trigger_error( $foo ) ); // OK.
wp_kses_allowed_html( _deprecated_function() ); // OK.

// These are no longer flagged as these are not the WP native escaping functions, so we don't know what parameter to check.
esc_something( trigger_error( $foo ) ); // OK.
wp_kses_page( _deprecated_function() ); // OK.

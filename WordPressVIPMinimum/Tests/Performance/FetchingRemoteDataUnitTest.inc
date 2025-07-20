<?php

/*
 * Not the sniff target.
 */
use function file_get_contents;

my\ns\file_get_contents($url);
$this->file_get_contents($url);
$this?->file_get_contents($file);
MyClass::file_get_contents($file);
echo FILE_GET_CONTENTS;
namespace\file_get_contents($url);


/*
 * These should all be okay.
 */
$file_content = file_get_contents( 'my-file.css' );
$file_content = \File_Get_Contents( /*comment*/ 'my-file.svg', );
$file_content = file_get_contents( __DIR__ . "/filename.css" );

// Looks like a function call, but is a PHP 8.0+ class instantiation via an attribute.
#[File_Get_Contents('text')]
function foo() {}

// Incomplete function call, should be ignored by the sniff.
$live_coding = file_get_contents();


/*
 * These should all be flagged with a warning.
 */
// Warning FileGetContentsRemoteFile.
$external_resource = file_get_contents( 'https://example.com', );
\file_get_contents( "http://$url" );
$external_resource = FILE_GET_CONTENTS(
	// Sort of protocol relative URL (the ":" is superfluous).
	'://example.com'
);
$external_resource = file_get_contents( 'HTTP://example.com', );

// Warning FileGetContentsUnknown.
file_get_contents( $url, true, $context, $offset, $length );
\File_GET_Contents( $obj->get_fileName() );
file_get_contents( MyClass::FILE_NAME, );

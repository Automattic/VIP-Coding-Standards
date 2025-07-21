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

file_get_contents(...$params); // PHP 5.6 argument unpacking. Warning FileGetContentsUnknown.

// Safeguard correct handling of function calls using PHP 8.0+ named parameters.
file_get_contents(context: $context, ); // OK, well, not really, missing required $filename param, but that's not the concern of this sniff.
file_get_contents(file: 'https://example.com',); // OK, well, not really, typo in param name, but that's not the concern of the sniff.
file_get_contents(
	context: stream_context_create(
		options: [
			$proxy => 'tcp://proxy.example.com:5100',
		],
	),
	filename: 'my-file.svg',
); // OK.

file_get_contents(
	context: stream_context_create(
		options: [
			$cafile => __DIR__ . '/cacert.pem',
		],
	),
	filename: 'https://example.com'
); // Warning FileGetContentsRemoteFile.
file_get_contents(
	context: stream_context_create(
		options: [
			$cafile => __DIR__ . '/cacert.pem',
		],
	),
	filename: $fileName,
); // Warning FileGetContentsUnknown.

array_walk($files, file_get_contents(...)); // PHP 8.1 first class callable. Warning FileGetContentsUnknown.

// Bug: don't throw a warning when __DIR__ is used as this indicates use with a local file.
\file_get_contents( __DIR__ . $filename ); // OK.

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

// Bug: check all text string tokens in the parameter.
$result = file_get_contents(( is_ssl() ? 'http' : 'https' ) . '://example.com'); // Warning FileGetContentsRemoteFile.

// Bug: don't presume if the parameter contains a text string token, that will be the only token.
\file_get_contents( $url . '/filename.css' ); // Warning FileGetContentsUnknown.

// Bug #506: the `php://` stream wrappers are local, in-process streams, not remote requests, so should not be flagged.
$webhook_body     = file_get_contents( 'php://input' ); // OK.
$std_input        = \file_get_contents( 'php://stdin' ); // OK.
$in_memory        = file_get_contents( "php://memory" ); // OK.
$temp_stream      = file_get_contents( 'php://temp/maxmemory:1048576' ); // OK.
$case_insensitive = file_get_contents( 'PHP://input' ); // OK. The scheme is case-insensitive.

// Bug #506: a `php://` substring which is not the URL scheme must still be flagged as remote.
$masked_remote = file_get_contents( 'https://example.com/?redirect=php://input' ); // Warning FileGetContentsRemoteFile.

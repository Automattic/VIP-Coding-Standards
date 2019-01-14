<?php

$file_content = file_get_contents( 'my-file.css' ); // OK.

$file_content = file_get_contents( 'my-file.svg' ); // OK.

$external_resource = file_get_contents( 'https://example.com' ); // NOK.
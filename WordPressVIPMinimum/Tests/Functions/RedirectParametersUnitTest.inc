<?php

wp_redirect( 'location', 302, 'Foo1' ); // OK.
wp_redirect(
	'location',     999,$my_foo
); // OK.
wp_redirect( 'location', 302 ); // Warning - third arg missing.

wp_safe_redirect( 'location', 302, "Foo4" ); // OK.
wp_safe_redirect(
	'location',     302,'Foo' . $foo
); // OK.
wp_safe_redirect( 'location', 306 ); // Warning - third arg missing.

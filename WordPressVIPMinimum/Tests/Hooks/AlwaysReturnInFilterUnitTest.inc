<?php

function good_example_function() { // Ok.
	if ( 1 === 0  ) {
		if ( 1 === 1 ) {
			return 'ahoj';
		} else {
			return 'hello';
		}
	}
	return 'This is fine.';
}
add_filter( 'good_example_function_filter', 'good_example_function' );

function bad_example_function() { // Error.
	if ( 1 === 0  ) {
		if ( 1 === 1 ) {
			return 'ahoj';
		} else {
			return 'hello';
		}
	}
	// Missing universal return.
}
add_filter( 'bad_example_function_filter', 'bad_example_function' );

class good_example_class { // Ok.
	public function __construct() {
		add_filter( 'good_example_class_filter', array( $this, 'class_filter' ) );
	}

	public function class_filter() {
		if ( 1 === 1 ) {
			if ( 1 === 0 ) {
				return 'whoops';
			} else {
				return 'here!';
			}
		}
		return 'This is Okay';
	}
}

class bad_example_class { // Error.
	public function __construct() {
		add_filter( 'bad_example_class_filter', array( $this, 'class_filter' ) );
	}

	public function class_filter() {
		if ( 1 === 1 ) {
			if ( 1 === 0 ) {
				return 'whoops';
			} else {
				return 'here!';
			}
		}
		// Missing universal return.
	}
}

if ( ! class_exists( 'another_good_example_class' ) ) { // Ok.
	class another_good_example_class {
		public function __construct() {
			add_filter( 'another_good_example_class_filter', array( $this, 'class_filter' ) );
		}

		public function class_filter() {
			if ( 1 === 1 ) {
				if ( 1 === 0 ) {
					return 'whoops';
				} else {
					return 'here!';
				}
			}
			return 'This is Okay';
		}
	}
}

add_filter( 'good_example_closure', function() { // Ok.
	if ( 1 === 0 ) {
		return 'Hello';
	} else {
		return 'Hey';
	}
} );

add_filter( 'bad_example_closure', function() { // Error.
	if ( 1 === 0 ) {
		return 'hey';
	}
	// Missing else return;
} );

add_filter( 'another_bad_example_closure', function() { // Error.
	return;
} );

if ( 1 === 1 ) {
	add_filter( 'another_good_example_closure', function () { // Ok.
		return 'hello';
	} );
}

add_filter( 'bad_example_closure_with_no_returns', function( $input ) { // Error.
	$input = $input . ' hello!';

	// But we never return _anywhere_
}, 10, 2 );

function good_example_arg_pass_by_ref( &$test ) { // Ok.
	if ( 1 === 0  ) {
		if ( 1 === 1 ) {
			return 'ahoj';
		} else {
			return $test . 'hello';
		}
	}
	// Missing universal return.
}
add_filter( 'good_example_filter', 'good_example_arg_pass_by_ref' );

add_filter( 'good_anonymous_example_arg_pass_by_ref', function( &$input ) { // Ok.
	$input = $input . ' hello!';

	// But we never return _anywhere_
}, 10, 2 );

function bad_example_arg( $test ) { // Error.
	if ( 1 === 0  ) {
		if ( 1 === 1 ) {
			return 'ahoj';
		} else {
			return $test . 'hello';
		}
	}
	// Missing universal return.
}
add_filter( 'bad_example_filter', 'bad_example_arg' );

class good_example_class_short_array { // Ok.
	public function __construct() {
		add_filter( 'good_example_class_filter', [ $this, 'class_filter' ] );
	}

	public function class_filter( $param ) {
		if ( 1 === 1 ) {
			if ( 1 === 0 ) {
				return 'whoops';
			} else {
				return 'here!';
			}
		}
		return 'This is Okay';
	}
}

class bad_example_class_short_array { // Error.
	public function __construct() {
		add_filter( 'bad_example_class_filter', [ $this, 'class_filter' ] );
	}

	public function class_filter( $param ) {
		if ( 1 === 1 ) {
			if ( 1 === 0 ) {
				return 'whoops';
			} else {
				return 'here!';
			}
		}
		// Missing universal return.
	}
}

abstract class warning_filter_points_to_abstract_method {
	public function __construct() {
		add_filter( 'good_example_abstract_class', [ $this, 'abstract_method' ] );
	}

	abstract public function abstract_method( $param ); // Warning.
}

class tokenizer_bug_test {
	public function __construct() {
		add_filter( 'tokenizer_bug', [ $this, 'tokenizer_bug' ] );
	}

	public function tokenizer_bug( $param ): namespace\Foo {} // Ok (tokenizer bug PHPCS < 3.5.7) / Error in PHPCS >= 3.5.7.
}

// Intentional parse error. This has to be the last test in the file!
class parse_error_test {
	public function __construct() {
		add_filter( 'parse_error', [ $this, 'parse_error' ] );
	}

	public function parse_error( $param ) // Ok, parse error ignored.

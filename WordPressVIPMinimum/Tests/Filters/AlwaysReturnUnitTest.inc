<?php

function good_example_function() {
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

function bad_example_function() {
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

class good_example_class {
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

class bad_example_class {
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

if ( ! class_exists( 'another_good_example_class' ) ) {
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

add_filter( 'good_example_closure', function() {
	if ( 1 === 0 ) {
		return 'Hello';
	} else {
		return 'Hey';
	}
} );

add_filter( 'bad_example_closure', function() {
	if ( 1 === 0 ) {
		return 'hey';
	}
	// Missing else return;
} );

add_filter( 'another_bad_example_closure', function() {
	return;
} );

if ( 1 === 1 ) {
	add_filter( 'another_good_example_closure', function () {
		return 'hello';
	} );
}
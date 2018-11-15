<?php

function foo() {
	$a = 'Hello';
	$c = compact( $a, $b );
}

trait bar {
	function foo() {
		$this->bar(); // Undefined $this in trait OK.
	}
	function bar() {}
}

function test() {
	try {
		do_something_silly();
	} catch ( Exception $e ) {} // OK.
}

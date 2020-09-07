<?php

function foo() {
	$a = 'Hello';
	$c = compact( $a, $b ); // Warning x2.
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

class MyClass {
	function my_function() {
		return function() {
			$this->my_callback(); // OK - new VariableAnalysis doesn't flag $this as undefined in closure.
		};
	}
 
	function my_callback() {}
 }

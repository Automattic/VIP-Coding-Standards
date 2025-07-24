<?php

/**
 * Not our targets.
 */
class DoesNotExtend {
	function foo() {}
}

class ExtendsDifferentClass extends FooBar {
	function foo() {}
}

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

class ExtendsFullyQualifiedClass extends \Different\NS\WP_Widget {
	function save_settings() {}
}

class ExtendsPartiallyQualifiedClass extends Also\Different\NS\Walker_Page {
	function save_settings() {}
}

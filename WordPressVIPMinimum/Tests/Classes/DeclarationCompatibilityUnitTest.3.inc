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

class NoMethods extends WP_Widget {}

class WidgetOkayNestedFunctionIsNotMethod extends WP_Widget {
	public function hasNested() {
		// Functions can be declared nested within other functions. That doesn't make them methods.
		function is_preview($extra_param) {}
	}
}

class WidgetOkayNestedClassDoesNotExtend extends WP_Widget {
	function hasNestedClass() {
		return new class {
			public function display_callback() {}
		};
	}
}

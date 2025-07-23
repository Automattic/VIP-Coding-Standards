<?php

// Test case file for the WP_Widget child class declarations.

class WidgetOkay extends WP_Widget {
	// Not a method we're interested in. Should be ignored.
	public function register_one($whatever, &$you, $want = null) {}

	// Methods which are being checked.
	public function widget( $args, $instance ) {}
	public function update( $new_instance, $old_instance ) {}
	public function form( $instance ) {}
	public function WP_Widget( $id_base, $name, $widget_options = array(), $control_options = array() ) {}
	public function get_field_name( $field_name ) {}
	public function get_field_id( $field_name ) {}
	public function _register() {}
	public function _set( $number ) {}
	public function _get_display_callback() {}
	public function _get_update_callback() {}
	public function _get_form_callback() {}
	public function is_preview() {}
	public function display_callback( $args, $widget_args = 1 ) {}
	public function update_callback( $deprecated = 1 ) {}
	public function form_callback( $widget_args = 1 ) {}
	public function _register_one( $number = -1 ) {}
	public function save_settings( $settings ) {}
	public function get_settings() {}
}

// These methods all change required parameters from the parent class into optional ones by adding a default value.
// This will not cause a signature mismatch in PHP since PHP 5.3.
readonly class WidgetOkayDefaultValueWhereParentHasNone extends WP_Widget {
	public function widget( $args = array(), $instance = array(), ) {}
	public function update( $new_instance = array(), $old_instance = array()) {}
	public function form( $instance = array() ) {}
	public function WP_Widget( $id_base = '', $name = '', $widget_options = array(), $control_options = array() ) {}
	public function get_field_name( $field_name = '' ) {}
	public function get_field_id( $field_name = '' ) {}
	public function _set( $number = 10 ) {}
	public function display_callback( $args = 2, $widget_args = 1 ) {}
	public function save_settings( $settings = array() ) {}
}

$WidgetOkayDifferentDefaultValue = new #[MyAttribute] readonly class extends \WP_Widget {
	public function WP_Widget( $id_base, $name, $widget_options = null, $control_options = null ) {}
	public function display_callback( $args, $widget_args = [] ) {}
	public function update_callback( $deprecated = true, ) {}
	public function form_callback( $widget_args = 2 ) {}
	public function _register_one( $number = 100 ) {}
};

class WidgetBadTooFewParamsNoParam extends WP_Widget {
	public function widget() {} // Bad, missing $args and $instance params.
	public function update() {} // Bad, missing $new_instance $old_instance params.
	public function form() {} // Bad, missing $instance param.
	public function WP_Widget() {} // Bad, missing $id_base, $name, $widget_options, $control_options params.
	public function get_field_name() {} // Bad, missing $field_name param.
	public function get_field_id() {} // Bad, missing $field_name param.
	public function _set() {} // Bad, missing $number param.
	public function display_callback() {} // Bad, missing $args, and $widget_args params.
	public function update_callback() {} // Bad, missing $depracated param.
	public function form_callback() {} // Bad, missing $widget_args param.
	public function _register_one() {} // Bad, missing $number param.
	public function save_settings() {} // Bad, missing $settings param.
}

final class WidgetBadTooFewParamsNotAllParams extends WP_WIDGET {
	public function widget( $args ) {} // Bad, missing $instance param.
	public function update( $new_instance ) {} // Bad, missing $old_instance param.
	public function WP_Widget( $id_base, $name, ) {} // Bad, missing $widget_options and $control_options params.
	public function display_callback( $args ) {} // Bad, missing $widget_args param.
}

$WidgetBadExtraRequiredParamsAndMethodNamesAreCaseInsensitive = new class() extends WP_Widget {
	public function Widget( $args, $instance, $extra_param ) {}
	public function Update( $new_instance, $old_instance, $extra_param ) {}
	public function Form( $intance, $extra_param ) {}
	public function wp_widget( $id_base, $name, $widget_options = array(), $control_options = array(), $extra_param) {}
	public function Get_Field_Name( $field_name, $extra_param ) {}
	public function Get_Field_ID( $field_name, $extra_param ) {}
	public function _Register( $extra_param ) {}
	public function _Set( $number, $extra_param ) {}
	public function _Get_Display_Callback($extra_paramA, $extra_paramB) {}
	public function _Get_Update_Callback( $extra_paramA, $extra_paramB ) {}
	public function _Get_Form_Callback($extra_param) {}
	public function is_Preview($extra_param) {}
	public function display_Callback( $args, $widget_args = 1, $extra_param ) {}
	public function update_Callback( $deprecated = 1, $extra_param, ) {}
	public function form_Callback( $widget_args = 1, $extra_param ) {}
	public function _register_ONE( $number = -1, $extra_param ) {}
	public function SAVE_SETTINGS( $settings, $extra_param) {}
	public function get_Settings( $extra_paramA, $extra_paramB, $extra_paramC ) {}
};

class WidgetBadOptionalParamChangedToRequiredMissingDefaultValue extends wp_widget {
	public function WP_Widget( $id_base, $name, $widget_options, $control_options = array() ) {} // Bad, missing default value for $widget_options.
	public function display_callback( $args, $widget_args ) {} // Bad, missing default value for $widget_args.
	public function update_callback( $deprecated ) {}
	public function form_callback( $widget_args ) {}
	public function _register_one( $number ) {}
}

class WidgetBadChildHasParamReferenceParentDoesNot extends \WP_Widget {
	public function widget( $args, &$instance ) {}
	public function update( &$new_instance, $old_instance ) {}
	public function form( &$instance ) {}
	public function WP_Widget( $id_base, &$name, $widget_options = array(), &$control_options = array() ) {}
	public function get_field_name( &$field_name ) {}
	public function get_field_id( &$field_name ) {}
	public function _set( &$number ) {}
	public function display_callback( &$args, $widget_args = 1 ) {}
	public function update_callback( &$deprecated = 1 ) {}
	public function form_callback( &$widget_args = 1 ) {}
	public function _register_one( &$number = -1 ) {}
	public function save_settings( &$settings ) {}
}

// It's perfectly valid for child classes to declare extra optional parameters.
class WidgetOkayExtraOptionalParams extends WP_Widget {
	public function widget( $args, $instance, $extra_param = array() ) {}
	public function update( $new_instance, $old_instance, ...$extra_param ) {}
	public function form( $instance, $extra_param = true ) {}
	public function WP_Widget( $id_base, $name, $widget_options = array(), $control_options = array(), $extra_param = array() ) {}
	public function get_field_name( $field_name, $extra_param = 'default') {}
	public function get_field_id( $field_name, $extra_param = 'id') {}
	public function _register( $extra_param = false ) {}
	public function _set( $number, $extra_param = 5 ) {}
	public function _get_display_callback($paramA = 'A', $paramB = 'B' ) {}
	public function _get_update_callback( $my_param = true, $my_param2 = 10) {}
	public function _get_form_callback(...$extra_param) {}
	public function is_preview( $is_preview = true ) {}
	public function display_callback( $args, $widget_args = 1, $extra_param = [] ) {}
	public function update_callback( $deprecated = 1, ...$extra_param ) {}
	public function form_callback( $widget_args = 1, ...$extra_param ) {}
	public function _register_one( $number = -1, $extra_param = []) {}
	public function save_settings( $settings, $extra_param = 10.5) {}
	public function get_settings( $settings = 'foo', $setting = self::BAR, ...$my_param ) {}
}

class WidgetOkayChildCanMakeLastParamVariadic extends WP_Widget {
	public function widget( $args, ...$instance ) {}
	public function update( $new_instance, ...$old_instance ) {}
	public function form( ...$instance ) {}
	public function WP_Widget( $id_base, $name, $widget_options = array(), ...$control_options ) {}
	public function get_field_name( ...$field_name ) {}
	public function get_field_id( ...$field_name ) {}
	public function _set( ...$number ) {}
	public function display_callback( $args, ...$widget_args ) {}
	public function update_callback( ...$deprecated ) {}
	public function form_callback( ...$widget_args ) {}
	public function _register_one( ...$number ) {}
	public function save_settings( ...$settings ) {}
}

class WidgetBadVariadicParamNotLast extends Wp_widget {
	public function widget( ...$args, $instance ) {}
	public function update( ...$new_instance, $old_instance ) {}
	public function WP_Widget( $id_base, $name, ...$widget_options = array(), $control_options = array() ) {}
	public function get_field_name( ...$field_name, $extra_param = null ) {}
	public function get_field_id( ...$field_name, $extra_param = null ) {}
	public function _set( ...$number, $extra_param = null ) {}
	public function display_callback( ...$args, $widget_args = 1 ) {}
	public function update_callback( ...$deprecated, $extra_param = null ) {}
	public function form_callback( ...$widget_args, $extra_param = null ) {}
	public function _register_one( ...$number, $extra_param = null ) {}
	public function save_settings( ...$settings, $extra_param = null ) {}
}

// The fact that all extra parameters are optional, should not hide other errors.
class WidgetBadExtraOptionalParamsANDOtherError extends WP_Widget {
	public function update_callback( $deprecated, $extra_param = [] ) {} // Missing default value for $deprecated.
	public function form_callback( &$widget_args, ...$extra_param ) {} // Invalid reference for $widget_args.
}

class WidgetBadNestedClassExtends {
	function nested() {
		return new class extends WP_Widget {
			public function display_callback() {}
		};
	}
}

class WidgetBadNestedClassAlsoExtends extends WP_Widget {
	function nested() {
		return new class extends WP_Widget {
			public function display_callback() {}
		};
	}
}


class WidgetOkayNoVisibilityMeansPublic extends WP_Widget {
	function widget( $args, $instance ) {}
	function update( $new_instance, $old_instance ) {}
	function form( $instance ) {}
	function WP_Widget( $id_base, $name, $widget_options = array(), $control_options = array() ) {}
	function get_field_name( $field_name ) {}
	function get_field_id( $field_name ) {}
	function _register() {}
	function _set( $number ) {}
	function _get_display_callback() {}
	function _get_update_callback() {}
	function _get_form_callback() {}
	function is_preview() {}
	function display_callback( $args, $widget_args = 1 ) {}
	function update_callback( $deprecated = 1 ) {}
	function form_callback( $widget_args = 1 ) {}
	function _register_one( $number = -1 ) {}
	function save_settings( $settings ) {}
	function get_settings() {}
}

class WidgetWrongVisibility extends WP_Widget {
	// Not a method we're interested in. Should be ignored.
	private function custom_method($whatever, $you, $want = null) {}

	protected function widget( $args, $instance ) {}
	private function update( $new_instance, $old_instance ) {}
	protected function form( $instance ) {}
	private function WP_Widget( $id_base, $name, $widget_options = array(), $control_options = array() ) {}
	protected function get_field_name( $field_name ) {}
	private function get_field_id( $field_name ) {}
	protected function _register() {}
	private function _set( $number ) {}
	protected function _get_display_callback() {}
	private function _get_update_callback() {}
	protected function _get_form_callback() {}
	private function is_preview() {}
	protected function display_callback( $args, $widget_args = 1 ) {}
	private function update_callback( $deprecated = 1 ) {}
	protected function form_callback( $widget_args = 1 ) {}
	private function _register_one( $number = -1 ) {}
	protected function save_settings( $settings ) {}
	private function get_settings() {}
}

class WidgetIncorrectReturnByRef extends WP_Widget {
	// Not a method we're interested in. Should be ignored.
	public function &custom_method($whatever, $you, $want = null) {}

	// Methods which are being checked.
	public function &widget( $args, $instance ) {}
	public function &update( $new_instance, $old_instance ) {}
	public function &form( $instance ) {}
	public function &WP_Widget( $id_base, $name, $widget_options = array(), $control_options = array() ) {}
	public function &get_field_name( $field_name ) {}
	public function &get_field_id( $field_name ) {}
	public function &_register() {}
	public function &_set( $number ) {}
	public function &_get_display_callback() {}
	public function &_get_update_callback() {}
	public function &_get_form_callback() {}
	public function &is_preview() {}
	public function &display_callback( $args, $widget_args = 1 ) {}
	public function &update_callback( $deprecated = 1 ) {}
	public function &form_callback( $widget_args = 1 ) {}
	public function &_register_one( $number = -1 ) {}
	public function &save_settings( $settings ) {}
	public function &get_settings() {}
}

// Parameter name mismatches cause issues with PHP 8.0 named parameters.
class WidgetBadParameterNameMismatch extends WP_Widget {
	public function widget( $arg, $intance ) {}
	public function update( $new, $old ) {}
	public function form( $inst ) {}
	public function WP_Widget( $base, $widget_name, $options = array(), $controls = array() ) {}
	public function get_field_name( $field ) {}
	public function get_field_id( $field ) {}
	public function _set( $num ) {}
	public function display_callback( $arg, $widgetArgs = 1 ) {}
	public function update_callback( $unused = 1 ) {}
	public function form_callback( $args = 1 ) {}
	public function _register_one( $nr = -1 ) {}
	public function save_settings( $setting ) {}
}

class WidgetOkayParameterTypesAreContraVariantAndMixedIsCompatibleWithNoType extends WP_Widget {
	public function widget( mixed $args, mixed $instance ) {}
	public function update( mixed $new_instance, mixed $old_instance ) {}
	public function form( mixed $instance ) {}
	public function WP_Widget( mixed $id_base, mixed $name, mixed $widget_options = array(), mixed $control_options = array() ) {}
	public function get_field_name( mixed $field_name ) {}
	public function get_field_id( mixed $field_name ) {}
	public function _set( mixed $number ) {}
	public function display_callback( mixed $args, mixed $widget_args = 1 ) {}
	public function update_callback( mixed $deprecated = 1 ) {}
	public function form_callback( mixed $widget_args = 1 ) {}
	public function _register_one( mixed $number = -1 ) {}
	public function save_settings( mixed $settings ) {}
}

class WidgetBadParameterTypesAreContraVariant extends WP_Widget {
	public function widget( array $args, array $instance ) {}
	public function update( array $new_instance, array $old_instance ) {}
	public function form( array $instance ) {}
	public function WP_Widget( string $id_base, string $name, array $widget_options = array(), array $control_options = array() ) {}
	public function get_field_name( string $field_name ) {}
	public function get_field_id( string $field_name ) {}
	public function _set( int $number ) {}
	public function display_callback( array $args, int|array $widget_args = 1 ) {}
	public function update_callback( int $deprecated = 1 ) {}
	public function form_callback( int|array $widget_args = 1 ) {}
	public function _register_one( int $number = -1 ) {}
	public function save_settings( array $settings ) {}
}

class WidgetOkayReturnTypesAreCovariantAndTypeMatchesExpectation extends WP_Widget {
	public function widget( $args, $instance ): void {}
	public function update( $new_instance, $old_instance ) : array|false {}
	public function form( $instance ) : string {}
	public function WP_Widget( $id_base, $name, $widget_options = array(), $control_options = array() ): void {}
	public function get_field_name( $field_name ) : string {}
	public function get_field_id( $field_name ) : string {}
	public function _register() : void {}
	public function _set( $number ): void {}
	public function _get_display_callback(): callable {}
	public function _get_update_callback() : callable {}
	public function _get_form_callback() : callable {}
	public function is_preview(): bool {}
	public function display_callback( $args, $widget_args = 1 ): int|array {}
	public function update_callback( $deprecated = 1 ): void {}
	public function form_callback( $widget_args = 1 ): string|null {}
	public function _register_one( $number = -1 ) : void {}
	public function save_settings( $settings ): void {}
	public function get_settings(): array {}
}

class WidgetOkayReturnTypesAreCovariantAndTypeMatchesExpectationToo extends WP_Widget {
	public function form( $instance ) : void {}
	public function display_callback( $args, $widget_args = 1 ): int {}
	public function form_callback( $widget_args = 1 ): ?string {}
}

class WidgetOkayReturnTypesAreCovariantAndTypeMatchesExpectationThree extends WP_Widget {
	public function display_callback( $args, $widget_args = 1 ): array {}
	public function form_callback( $widget_args = 1 ): string {}
}

class WidgetOkayReturnTypesAreCovariantAndTypeMatchesExpectationFour extends WP_Widget {
	public function form_callback( $widget_args = 1 ): null {}
}

class WidgetOkayReturnTypesAreCovariantAndMixedWillAlwaysWork extends WP_Widget {
	public function widget( $args, $instance ): mixed {}
	public function update( $new_instance, $old_instance ) : mixed {}
	public function form( $instance ) : mixed {}
	public function WP_Widget( $id_base, $name, $widget_options = array(), $control_options = array() ): mixed {}
	public function get_field_name( $field_name ) : mixed {}
	public function get_field_id( $field_name ) : mixed {}
	public function _register() : mixed {}
	public function _set( $number ): mixed {}
	public function _get_display_callback(): mixed {}
	public function _get_update_callback() : mixed {}
	public function _get_form_callback() : mixed {}
	public function is_preview(): mixed {}
	public function display_callback( $args, $widget_args = 1 ): mixed {}
	public function update_callback( $deprecated = 1 ) : mixed {}
	public function form_callback( $widget_args = 1 ) : mixed {}
	public function _register_one( $number = -1 ) : mixed {}
	public function save_settings( $settings ) : mixed {}
	public function get_settings() : mixed {}
}

class WidgetBadReturnTypeDoesNotMatchExpectation extends WP_Widget {
	// Not a method we're interested in. Should be ignored.
	public function custom_method($whatever, $you, $want = null): never {}

	// Methods which are being checked.
	public function widget( $args, $instance ) : string {}
	public function update( $new_instance, $old_instance ): void {}
	public function form( $instance ): int {}
	public function WP_Widget( $id_base, $name, $widget_options = array(), $control_options = array() ): null {}
	public function get_field_name( $field_name ): false {}
	public function get_field_id( $field_name ): string|true {}
	public function _register() : bool {}
	public function _set( $number ) : int {}
	public function _get_display_callback() : ?callable {}
	public function _get_update_callback(): object {}
	public function _get_form_callback(): array {}
	public function is_preview(): float {}
	public function display_callback( $args, $widget_args = 1 ): int|array|null {}
	public function update_callback( $deprecated = 1 ) : ?float {}
	public function form_callback( $widget_args = 1 ) : ?int {}
	public function _register_one( $number = -1 ) : never {}
	public function save_settings( $settings ) : ?bool {}
	public function get_settings() : iterable {}
}

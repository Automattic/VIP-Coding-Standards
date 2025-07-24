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

class WidgetOkayDifferentDefaultValue extends WP_Widget {
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

final class WidgetBadTooFewParamsNotAllParams extends WP_Widget {
	public function widget( $args ) {} // Bad, missing $instance param.
	public function update( $new_instance ) {} // Bad, missing $old_instance param.
	public function WP_Widget( $id_base, $name, ) {} // Bad, missing $widget_options and $control_options params.
	public function display_callback( $args ) {} // Bad, missing $widget_args param.
}

class WidgetBadExtraRequiredParams extends WP_Widget {
	public function widget( $args, $instance, $extra_param ) {}
	public function update( $new_instance, $old_instance, $extra_param ) {}
	public function form( $instance, $extra_param ) {}
	public function WP_Widget( $id_base, $name, $widget_options = array(), $control_options = array(), $extra_param) {}
	public function get_field_name( $field_name, $extra_param ) {}
	public function get_field_id( $field_name, $extra_param ) {}
	public function _register( $extra_param ) {}
	public function _set( $number, $extra_param ) {}
	public function _get_display_callback($extra_paramA, $extra_paramB) {}
	public function _get_update_callback( $extra_paramA, $extra_paramB ) {}
	public function _get_form_callback($extra_param) {}
	public function is_preview($extra_param) {}
	public function display_callback( $args, $widget_args = 1, $extra_param ) {}
	public function update_callback( $deprecated = 1, $extra_param, ) {}
	public function form_callback( $widget_args = 1, $extra_param ) {}
	public function _register_one( $number = -1, $extra_param ) {}
	public function save_settings( $settings, $extra_param) {}
	public function get_settings( $extra_paramA, $extra_paramB, $extra_paramC ) {}
};

class WidgetBadOptionalParamChangedToRequiredMissingDefaultValue extends WP_Widget {
	public function WP_Widget( $id_base, $name, $widget_options, $control_options = array() ) {} // Bad, missing default value for $widget_options.
	public function display_callback( $args, $widget_args ) {} // Bad, missing default value for $widget_args.
	public function update_callback( $deprecated ) {}
	public function form_callback( $widget_args ) {}
	public function _register_one( $number ) {}
}

class WidgetBadChildHasParamReferenceParentDoesNot extends WP_Widget {
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

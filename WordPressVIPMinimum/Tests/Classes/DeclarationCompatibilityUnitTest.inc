<?php

class MyWidget extends WP_Widget {
	function widget() {
	} // Bad, missing $args and $instance params.

	function update() {
	} // Bad, missing $new_instance $old_instance params.

	function form() {
	} // Bad, missing $instance param.

	function WP_Widget() {
	} // Bad, missing $id_base, $name, $widget_options, $control_options params.

	function get_field_name() {
	} // Bad, missing $field_name param.

	function get_field_id() {
	} // Bad, missing $field_name param.

	function _register() {
	} // OK, no params.

	function _set() {
	} // Bad, missing $number param.

	function _get_display_callback() {
	} // OK, no params.

	function _get_update_callback() {
	} // Ok, no params.

	function _get_form_callback() {
	} // OK, no params.

	function is_preview() {
	} // OK, no params.

	function display_callback() {
	} // Bad, missing $args, and $widget_args params.

	function update_callback() {
	} // Bad, missing $depracated param.

	function form_callback() {
	} // Bad, missing $widget_args param.

	function register_one() {
	} // Bad, missing $number param.

	function save_settings() {
	} // Bad, missing $settings param.

	function get_settings() {
	} // OK, no params.

}

class MyWidget2 extends WP_Widget {
	function widget( $args ) {
	} // Bad, missing $instance param.

	function update( $new_instance, $old_instance ) {
	} // OK.

	function form( $intance, $my_param ) {
	} // Bad, extra $my_param param.

	function WP_Widget( $id_base, $name, $widget_options, $control_options = array() ) {
	} // Bad, missing default value for $widget_options.

	function get_field_name( $field_name ) {
	} // Ok.

	function get_field_id( $field_name, $my_param ) {
	} // Bad, extra $my_param param.

	function _register( $my_param ) {
	} // Bad, extra $my_param param.

	function _set( $number ) {
	} // Ok.

	function _get_display_callback() {
	} // OK, no params.

	function _get_update_callback( $my_param, $my_param2 ) {
	} // Bad, extra $my_param and $my_param2 params.

	function _get_form_callback() {
	} // OK, no params.

	function is_preview( $is_preview = true ) {
	} // OK, extra $is_preview param, but with default value.

	function display_callback( $args = 2, $widget_args = 1 ) {
	} // Ok, despite $args having a default value.

	function update_callback( $depracated = true ) {
	} // Ok.

	function form_callback( $widget_args = 2 ) {
	} // Ok, despite having different default value.

	function register_one( $number ) {
	} // Bad, the param should have a default value. Should be $number.

	function save_settings( $setting ) {
	} // Ok, despite wrong param name, which should be $settings.

	function get_settings( $settings, $setting, $my_param ) {
	} // Bad, 3 extra params.

}

class MyWalker extends Walker {

	function start_lvl( $output, $depth, $args = array() ) {
	} // Bad. $output should be passed by reference, $depth should have a default value.

	function end_lvl( &$output, $depth = 0, $args = array() ) {
	} // Ok.

	function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
	} // Ok.

	function unset_children( $el, $children_elements ) {
	} // Bad. $children_elements should be passed by reference
}
<?php

// Test case file for the Walker child class declarations.

class WalkerOkay extends Walker {
	// Not a method we're interested in. Should be ignored.
	public function custom_method($whatever, &$you, $want = null) {}

	// Methods which are being checked.
	public function start_lvl( &$output, $depth = 0, $args = array() ) {}
	public function end_lvl( &$output, $depth = 0, $args = array() ) {}
	public function start_el( &$output, $data_object, $depth = 0, $args = array(), $current_object_id = 0 ) {}
	public function end_el( &$output, $data_object, $depth = 0, $args = array(), ) {}
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {}
	public function walk( $elements, $max_depth, ...$args ) {}
	public function paged_walk( $elements, $max_depth, $page_num, $per_page, ...$args ) {}
	public function get_number_of_root_elements( $elements, ) {}
	public function unset_children( $element, &$children_elements ) {}
}

// These methods all change required parameters from the parent class into optional ones by adding a default value.
// This will not cause a signature mismatch in PHP since PHP 5.3.
final class WalkerOkayDefaultValueWhereParentHasNone extends Walker_Page {
	public function start_lvl( &$output = '', $depth = 0, $args = array() ) {}
	public function end_lvl( &$output = '', $depth = 0, $args = array() ) {}
	public function start_el( &$output = '', $data_object = new stdClass, $depth = 0, $args = array(), $current_object_id = 0 ) {}
	public function end_el( &$output = '', $data_object = new stdClass(), $depth = 0, $args = array() ) {}
	public function display_element( $element = new stdClass(), &$children_elements = array(), $max_depth = 0, $depth = 0, $args = array(), &$output = '' ) {}
	public function walk( $elements = array(), $max_depth = 0, ...$args ) {}
	public function paged_walk( $elements = array(), $max_depth = 0, $page_num = 1, $per_page = 10, ...$args ) {}
	public function get_number_of_root_elements( $elements = array() ) {}
	public function unset_children( $element = new stdClass, &$children_elements = array(), ) {}
}

class WalkerOkayDifferentDefaultValue extends Walker_Category_Checklist {
	public function start_lvl( &$output, $depth = 2, $args = null ) {}
	public function end_lvl( &$output, $depth = 1, $args = new stdClass ) {}
	public function start_el( &$output, $data_object, $depth = 5, $args = null, $current_object_id = 10 ) {}
	public function end_el( &$output, $data_object, $depth = 10, $args = '' ) {}
};

class WalkerBadTooFewParamsNoParamAndMethodNamesAreCaseInsensitive extends Walker_Category {
	public function start_LVL() {}
	public function End_Lvl() {}
	public function START_EL() {}
	public function end_EL() {}
	public function Display_Element() {}
	public function WalK() {}
	public function paged_WALK() {}
	public function get_Number_Of_Root_Elements() {}
	public function unset_Children() {}
}

readonly class WalkerBadTooFewParamsNotAllParams extends walker_categorydropdown {
	public function start_lvl( &$output, $depth = 0 ) {} // Bad, missing $args param.
	public function end_lvl( &$output ) {} // Bad, missing $depth and $args params.
	public function start_el( &$output, $data_object, $depth = 0, ) {} // Bad, missing $args and $current_object_id params.
	public function end_el( &$output, $data_object ) {} // Bad, missing $depth and $args params.
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, ) {} // Bad, missing $output param.
	public function walk( $elements, $max_depth ) {} // Bad, missing $args param.
	public function paged_walk( $elements, $max_depth, $page_num ) {} // Bad, missing $per_page and $args params.
	public function unset_children( $element ) {} // Bad, missing $children_elements param.
}

class WalkerBadExtraRequiredParams extends Walker_PageDropdown {
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output, $extra_param ) {}
	public function get_number_of_root_elements( $elements, $extra_param, ) {}
	public function unset_children( $element, &$children_elements, $extra_paramA, $extra_paramB ) {}
}

class WalkerBadOptionalParamChangedToRequiredMissingDefaultValue extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth, $args ) {}
	public function end_lvl( &$output, $depth, $args = array() ) {}
	public function start_el( &$output, $data_object, $depth, $args, $current_object_id ) {}
	public function end_el( &$output, $data_object, $depth, $args ) {}
}

class WalkerBadMissingReferences extends Walker_Comment {
	public function start_lvl( $output, $depth = 0, $args = array() ) {}
	public function end_lvl( $output, $depth = 0, $args = array() ) {}
	public function start_el( $output, $data_object, $depth = 0, $args = array(), $current_object_id = 0 ) {}
	public function end_el( $output, $data_object, $depth = 0, $args = array() ) {}
	public function display_element( $element, $children_elements, $max_depth, $depth, $args, &$output ) {}
	public function unset_children( $element, $children_elements, ) {}
}

class WalkerBadMissingReferencesToo extends Walker_Comment {
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, $output ) {}
}

class WalkerBadVariadicArgumentNotDeclaredAsVariadic extends Walker {
	public function walk( $elements, $max_depth, $args ) {}
	public function paged_walk( $elements, $max_depth, $page_num, $per_page, $args ) {}
}

class WalkerBadChildHasParamReferenceParentDoesNot extends Walker_Category {
	public function start_lvl( &$output, &$depth = 0, $args = array() ) {}
	public function end_lvl( &$output, $depth = 0, &$args = array() ) {}
	public function start_el( &$output, $data_object, $depth = 0, $args = array(), &$current_object_id = 0 ) {}
	public function end_el( &$output, $data_object, &$depth = 0, $args = array(), ) {}
	public function display_element( &$element, &$children_elements, $max_depth, $depth, $args, &$output ) {}
	public function walk( $elements, &$max_depth, ...$args ) {}
	public function paged_walk( $elements, $max_depth, &$page_num, $per_page, ...$args ) {}
	public function get_number_of_root_elements( &$elements, ) {}
	public function unset_children( &$element, &$children_elements ) {}
}

// It's perfectly valid for child classes to declare extra optional parameters.
class WalkerOkayExtraOptionalParams extends Walker_CategoryDropdown {
	public function start_lvl( &$output, $depth = 0, $args = array(), $extra_param = true ) {}
	public function end_lvl( &$output, $depth = 0, $args = array(), ...$extra_param ) {}
	public function start_el( &$output, $data_object, $depth = 0, $args = array(), $current_object_id = 0, $extra_param = array() ) {}
	public function end_el( &$output, $data_object, $depth = 0, $args = array(), $extra_param = [], ) {}
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output, $extra_param = 'default' ) {}
	public function walk( $elements, $max_depth, $extra_param = null, ...$args ) {}
	public function paged_walk( $elements, $max_depth, $page_num, $per_page, $extra_param = null, ...$args ) {}
	public function get_number_of_root_elements( $elements, $extra_param = 10, ) {}
	public function unset_children( $element, &$children_elements, $extra_param = '', ) {}
}

class WalkerOkayChildCanMakeLastParamVariadic extends Walker_Page {
	public function start_lvl( &$output, $depth = 0, ...$args ) {}
	public function end_lvl( &$output, $depth = 0, ...$args ) {}
	public function start_el( &$output, $data_object, $depth = 0, $args = array(), ...$current_object_id ) {}
	public function end_el( &$output, $data_object, $depth = 0, ...$args, ) {}
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &...$output ) {}
	public function get_number_of_root_elements( ...$elements, ) {}
	public function unset_children( $element, &...$children_elements ) {}
}

class WalkerBadVariadicParamNotLast extends WALKER_NAV_MENU {
	public function start_lvl( &$output, ...$depth, $args = array() ) {}
	public function end_lvl( &...$output, $depth = 0, $args = array() ) {}
	public function start_el( &$output, ...$data_object, $depth = 0, $args = array(), $current_object_id = 0 ) {}
	public function end_el( &$output, $data_object, ...$depth, $args = array(), ) {}
	public function display_element( $element, &$children_elements, ...$max_depth, $depth, $args, &$output ) {}
	public function get_number_of_root_elements( ...$elements, $extra_param = 10, ) {}
	public function unset_children( ...$element, &$children_elements ) {}
}

// The fact that all extra parameters are optional, should not hide other errors.
class WalkerBadExtraOptionalParamsANDOtherError extends Walker_Category_Checklist {
	public function start_lvl( $output, $depth = 0, $args = array(), $extra_param = true ) {} // Missing reference for $output.
	public function end_lvl( &$output, $depth, $args = array(), ...$extra_param ) {} // Missing default value for $depth.
}

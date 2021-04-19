<?php

echo <<<EOT
<script>
 _.templateSettings = {
	interpolate: /\{\{(.+?)\}\}/g
};
 </script>
EOT;

?>
<script type="text/template" id="js-tpl--example">
			<a
				href="<%= post_url %>"> <!-- NOK. -->
				<h3><%- post_title %></h3><!-- OK -->
			</a>
</script>

<?php

function single_quoted_string() {
	echo '<div class="thumb" data-attid="<%=   data.id   %>" data-origsrc="<%- data.originalSrc %>">'. // NOK x 1.
		'<img src="<%- data.src %>" alt="<%=data.alt%>"/>'. // NOK x 1.
	'</div>';
}

function single_quoted_string_with_concatenation( $data ) {
	echo '<img src="<%= ' . $data['src'] . ' %>" alt="<%- data.alt %>"/>'; // NOK x 1.
}

function double_quoted_string( $name, $value, $is_template ) {
	echo $is_template ? "<%={$name}%>" : esc_attr( $value ); // NOK.
}

$nowdoc = <<<'EOT'
<script type="text/template" id="prefix-template">
	<section class="prefix-photo prefix-image">
		<img src="<%= img.src %>" class="prefix-image" width="<%= img.width %>" height="<%= img.height %>" /><!-- NOK x 3 -->
	</section>
</script>
EOT;

$heredoc = <<<EOD
	<label
		class="$classes prefix-form-ui-label-<%- htmlAttr.id %><%= ordinal %>"
		for="<%- htmlAttr.id %><%= ordinal %>">
		<%= name %>
	</label><!-- NOK - 1 per line -->
EOD;

// Make sure the JS specific check does not trigger on PHP code.
$obj->interpolate = true;

// Test matching the "interpolate" keyword with higher precision (mirrors same check in JS).
function test_interpolate_match_precision() {
	?>
	<script type="text/javascript">
	_.templateSettings.interpolate = /\{\{(.+?)\}\}/g; /* NOK */

	options.interpolate=_.templateSettings.interpolate; /* NOK */
	var interpolate = options.interpolate || reNoMatch, /* Ignore */
		source = "__p += '";

	// Prevent false positives on "interpolate".
	var preventMisidentification = 'text interpolate text'; // OK.
	var interpolate = THREE.CurveUtils.interpolate; // OK.

	var p = function(f, d) {
		return s.interpolate(m(f), _(d), 0.5, e.color_space) // OK.
	}

	y.interpolate.bezier = b; // OK.
	</script>
	<?php
}

// Recognize escaping.
function dont_trigger_when_escaped() {
	$script = <<<EOD
		var html = _.template('<li><%= _.escape(name) %></li>', { name: 'John Smith' }); // OK.
		
		var html = _.template(
			"<pre>The \"<% __p+=_.escape(o.text) %>\" is the same<br />" + // OK.
				"as the  \"<%= _.escape(o.text) %>\" and the same<br />" + // OK.
				"as the \"<%- o.text %>\"</pre>", // OK.
			{
				text: "<b>some text</b> and \n it's a line break"
			},
			{
				variable: "o"
			}
		);
EOD;

	echo $script;
}

function display_foo {
?>
	<script id="template" type="text/template">
		<li class="dashboard-post-item" dashboard-id="<%= _.escape( id ) %>"><!-- OK -->
			<div class="image-wrapper">
				<img src="<%= _.escape( image_url ) %>" class="dashboard-image"><!-- OK -->
			</div>
		</li>
	</script>
<?php
}

function print_foo() {
?>
	<script id="template" type="text/template">
		var compiled = _.template("<% print('Hello ' + _.escape(epithet)); %>"); /* OK */
		var compiled = _.template("<% print('Hello ' + epithet); %>"); /* NOK */
		var compiled = _.template("<% __p+=o.text %>"); /* NOK */

		compiled({epithet: "stooge"});
	</script>
<?php
}

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

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
				<h3><%- post_title %></h3>
			</a>
</script>

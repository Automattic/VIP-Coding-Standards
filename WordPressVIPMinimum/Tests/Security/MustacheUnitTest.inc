<?php
echo '<a href="{{href}}">{{{data}}}</div></a>'; // NOK: data.
echo '<a href="{{href}}">{{&data}}</div></a>'; // NOK: data.
?>
<script type="text/html" id="tmpl-example">
<a href="{{href}}">{{{data}}}</div></a><!-- NOK: data. -->
<a href="{{href}}">{{&data}}</div></a><!-- NOK: data. -->
{{=<% %>=}} <!-- NOK: delimiter change -->
</script>

<script>
	andlebars.registerHelper('link', function(text, url) {
		text = Handlebars.Utils.escapeExpression(text);
		url  = Handlebars.Utils.escapeExpression(url);

		var result = '<a href="' + url + '">' + text + '</a>';

		return new Handlebars.SafeString(result); // NOK: SafeString.
	});
</script>

// Issue 541#issuecomment-1692323177: don't flag GB syntax.
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"top":"0","right":"0","bottom":"var:preset|spacing|medium","left":"0"}}}} --><!-- OK. -->

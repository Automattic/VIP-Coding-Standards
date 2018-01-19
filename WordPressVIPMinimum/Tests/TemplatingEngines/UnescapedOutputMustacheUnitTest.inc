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
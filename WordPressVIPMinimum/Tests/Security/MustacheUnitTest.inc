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

<?php
echo "<a href='${foo->{$baz}}'>{{{data}}}</div></a>"; // NOK: data.

echo <<<HERE
<script type="text/html" id="tmpl-example">
<a href="${(href)}">{{{data}}}</div></a><!-- NOK: data. -->
<a href="{{href}}">{{&data}}</div></a><!-- NOK: data. -->
HERE;
echo <<<"HERE"
{{=<% %>=}} <!-- NOK: delimiter change -->
</script>
HERE;

echo <<<'NOW'
<script type="text/html" id="tmpl-example">
<a href="{{href}}">{{{data}}}</div></a><!-- NOK: data. -->
<a href="{{href}}">{{&data}}</div></a><!-- NOK: data. -->
{{=<% %>=}} <!-- NOK: delimiter change -->
</script>
NOW;

// Don't throw false positives on incorrect/incomplete mustache delimiter change.
$m = '{{=<%'; // Missing end of delimiter change.
$m = '%>=}} {{=<%'; // Incorrect order, not a delimiter change.

// Correctly recognize mid-line delimiter change.
$m = '{{default_tags}} {{=<% %>=}} <% erb_style_tags %> <%={{ }}=%> {{ default_tags_again }}'; // NOK: delimiter change.

// Prevent false positives on SafeString being a partial name.
echo 'MySafeString';

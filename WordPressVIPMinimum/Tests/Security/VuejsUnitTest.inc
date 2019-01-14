<?php

?>
<article v-for="post in posts" class="listing-post">
	<a v-bind:href="'/research'+post.permalink" class="column-4"><h4 v-html="post.title"></h4></a>
</article>
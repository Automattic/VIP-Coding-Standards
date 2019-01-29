# Using `get_children()`

> Similar to get_posts(), but also performs a no-LIMIT query among other bad things by default. Alias of break_my_site_now_please(). Do not use. Instead do a regular WP_Query and make sure that the post_parent you are looking for is not 0 or a falsey value. Also make sure to set a reasonable posts_per_page, get_children will do a -1 query by default, a maximum of 100 should be used (but a smaller value could increase performance)

`get_children()` is very similar to [`get_posts()`](suppress_filters.md) with the additional caveat where it, by default, performs a no-LIMIT query. For more information on uncached functions, you can [visit the official VIP docs here](https://vip.wordpress.com/documentation/vip-go/uncached-functions/).

## Setting `suppress_filters => false`

Consider the following example:

```php
// Get images associated with a post
$posts = get_posts( array(
	'order' => 'ASC',
	'post_mime_type' => 'image',
	'post_parent' => 123,
	'post_status' => null,
	'post_type' => 'attachment',
) );
```

In order to make sure the query is cached and it isn't limitless, `get_children` should be used like in the example below:

```php
// Get images associated with a post
$posts = get_posts( array(
	'order' => 'ASC',
	'post_mime_type' => 'image',
	'post_parent' => 123,
	'post_status' => null,
	'post_type' => 'attachment',
	'suppress_filters' => false, // This will make sure that the caching filters will run, caching the query.
	`posts_per_page` => 10, // Make sure that the query has a limit
) );
```


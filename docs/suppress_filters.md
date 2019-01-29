# Setting `suppress_filters` to `false`

WordPress core has a number of functions that, for various reasons, are uncached, which means that calling them will always result in an SQL query. 

Unlike `WP_Query`, the results of `get_posts()` are not cached via Advanced Post Cache. You should use `WP_Query` instead, or set `'suppress_filters' => false`.

When using `WP_Query` instead of `get_posts()` don’t forget about setting `ignore_sticky_posts` and `no_found_rows` parameters appropriately, as both are hardcoded inside `get_posts()` function with value of `true`. 

To get more information on uncached functions, you can [visit the official VIP docs here](https://vip.wordpress.com/documentation/vip-go/uncached-functions/).

## Setting `suppress_filters => false`

Consider the following example:

```php
// Get 10 posts from category 1
$posts = get_posts( array(
	'posts_per_page' => 10,
	'category' => 1,
) );
```

Using this function with these arguments would bypass the object cache, as the `suppress_filters` parameter is set as `true` by default. When calling this function you should set this parameter to false:

```php
// Get 10 posts from category 1
$posts = get_posts( array(
	'posts_per_page' => 10,
	'category' => 1,
	'suppress_filters' => false; // This will make sure that the caching filters will run, caching the query.
) );
```

## Other functions

There are other functions that should have the `suppress_filters` parameter set to `false`. The usage is similar as the example above.

 * `wp_get_recent_posts()`
 * `get_children()` although there are some caveats with this particular function. [Click here](get_children.md) for more information.
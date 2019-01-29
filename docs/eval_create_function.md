# eval() and create_function()

Both these functions can execute arbitrary code that’s constructed at run time, which can be created through difficult-to-follow execution flows. These methods can make your site fragile because unforeseen conditions can cause syntax errors in the executed code, which becomes dynamic. A much better alternative is an Anonymous Function, which is hardcoded into the file and can never change during execution.

If there are no other options than to use this construct, pay special attention not to pass any user provided data into it without properly validating it beforehand.

We strongly recommend using Anonymous Functions, which are much cleaner and more secure.

## Using Anonymous Functions

Consider this function created using `create_function`:

```php
$func = create_function('$a,$b', 'return $a + $b');
$value = $func(2, 3); // returns 5
```

The right way to create this function using a anonymous function would be:

```php
$func = function( $a, $b ) { return $a + $b; };
$value = $func(2, 3); // returns 5
```

For more information on Anonymous Functions, you can [visit the PHP documentation](http://php.net/manual/en/functions.anonymous.php).
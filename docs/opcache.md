# OpCache Functions

Using the opcache PHP functions in application code can cause problems on hosting at scale. This includes but isn't limited to PHP fatals caused by corrupted memory.

Examples include `opcache_reset`, `opcache_invalidate`, and `opcache_compile_file`, [amongst others](http://php.net/manual/en/book.opcache.php).

Use of these functions should be reserved for platform maintenance purposes, and should never be used in a browser context.
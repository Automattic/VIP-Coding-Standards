# Including Files

Including PHP files carries some level of risk, with vectors such as directory traversal attacks, etc

Below are a number of issues that get flagged and why.

## Using a Variable

Including files via variables, can sometimes be safe, but it's also a way to sneak in malicious snippets from other sources. This is an avenue for directory traversal attacks.

Not all uses of variables face this however, so manual inspection is necessary.

## Using a Custom Constant

Custom constants can make including files from subfolders easier to read in code. The problem arises when these custom constants have name clashes, aren't defined early enough, or are misused.

A good way to avoid this is to use an autoloader, but it would be wise to manually inspect usage to ensure safety.

## Using a Custom Function

File inclusion using custom functions can be problematic. Internally they can use insecure methods to retrieve the file, or return external URLs.

This may be a problem, a good way to avoid this however is to use an autoloader, or run `include` inside the function so that it's clearer what's happening.

## Not Absolute Path

When including a file without using an absolute path, the assumption is that it will be relative to the current file you're working in, but this isn't always the case. This can lead to problems, such as the wrong file being included, or files not being found.

Instead, use `get_template_directory()`, `get_stylesheet_directory()` or `plugin_dir_path()` to turn it into an absolute path, or, use an autoloader.

## External URL

Including a file via PHP poses a security risk. Aside from bypassing code review, the server at the other end must be trusted 100%, as well as the connection to it. This means there is a high risk of man in the middle attacks.

### Performance

In order to load a remote file, a remote request has to be made. This can be one of the most expensive things that a PHP request can do, and leaves your sites performance at the mercy of internet traffic. This can add seconds to the page load times for each remote inclusion.

### Reliability

If the remote server goes down, the inclusion of the file fails, preventing the request from being served. This makes including remote files fragile and prone to failure.

## Restricted Constant

The `TEMPLATEPATH` or `STYLESHEETPATH` may not be defined or available when used. Instead, it's better to use `get_template_directory` and `get_stylesheet_directory` respectively.







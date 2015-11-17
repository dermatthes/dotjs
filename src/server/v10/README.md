# Api Reference: dotJS V10

This document provides information about the server-side api of dotJS.

## Global functions

All global functions are located within the object `DOT`.

### Outputing content

Use the `DOT.print()` function to print data just to the servers output-stream.

Example:

```
DOT.print("Hello World");
```

### Loading JavaScript from file

You can use `DOT.include("someFilename.js")` to run the script located in this file. To see the
filename or path of the script you can use the superglobal `__DIR__` and `__FILE__` to see the
full path of the current script.

Example:

```
<script remote="yes">
DOT.print("I am in file: " + __FILE__);
DOT.include(__DIR__ . "/someOtherFileInSameDirectory.js");
</script>
```


## Extensions

You can load a extension library by calling `DOT.include("dot://dir/file.js")` to include the
library source to the current process.

### Database Extension

The database extension lets you speak directly to a sql server. It supports prepare statements
and supports sequential processing of large datasets (Using `forEach()` with callbacks)

Example:

```
DOT.include("dot://DB/DB.js");
DOT.DB.query("SELECT * FROM User WHERE id > ?", 5).forEach(
    function (data) {
        DOT.print("<br>New row: " + data.name);
    }
)->exec();
```

### Session Extension

### Http Extension

Lets you read, modify and change HTTP headers of the response.

### Controller Extension




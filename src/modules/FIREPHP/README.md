# FirePHP Logging Module

Brings the browser-side logging mechanisms of `console.log` to the server-side.
 
## Activating

Activating FirePHP Logging is easy. Just install the Firefox-Extension on [firefox extension page](https://addons.mozilla.org/de/firefox/addon/firephp/).

On the server side add:

```
DOT.include("dot://FIREPHP/FIREPHP.js");
```

That's it.

## Usage

You can use the global `LOG` object to access the logging mechanisms.

```
LOG.debug("someMessage")
LOG.info("someMessage")
LOG.warn("someMessage");
LOG.error("someMessage");
```


### Sending Table-Data

Sometimes you might want to send row-data as table

```
LOG.table("some Label")
    .header(["Name", "Street", "ZIP"])
    .row(["SomeName", "SomeStreet", "SomeZip"])
    .row(["SomeName", "SomeStreet", "SomeZip"])
    .out();
```

or, if you have already an array of array elements:

```
LOG.table("Some label", [
        ["SomeName", "SomeStreet", "SomeZip"],
        ["SomeName", "SomeStreet", "SomeZip"]
    ]).header(["Name", "Street", "ZIP"]).out();
```


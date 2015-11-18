# dotJS - V8JS powered template engine

dotJS compiles angular-style templates on server-side. It lets you program both, server and client site in
pure JavaScript.

Requirements:

- *PHP 5.5*: PHP Interpreter
- *V8Js*: The V8 connector for PHP

## dotJS for template-designers

This section will show how to use dotJS to compile your html content to javascript and
execute it on googles V8 JavaScript engine.

### Executing code server-side

```
<body>
    <script language="JavaScript" remote="yes">
        DOT.print("Hello World");
    </script>
</body>
```

will compile to

```
<body>
    Hello World
</body>
```

### Loops and conditions

```
<body>
    <div dot-if="data.isOnError">
        Error: [[ data.errorMsg ]] Code: [[ data.errorCode ]]
    </div>
    <div dot-repeat="data.someArrayData as curLine">
        <b>Line [[ index ]]: [[ curLine ]]
    </div>
</body>
```

### Using includes

```
<include file="someFile.tpl.html"/>
```

## Using the Template Engine (inheritance)

dotJS supports Twig-like template inheritance.

mainLayout.tpl.html:
```
<html>
    <head>
        <title dot-block="titleContent">Default title</title>
    </head>
    <body dot-block="mainContent">
        No content available
    </body>
</html>
```

home.html
```
<template use="mainLayout.html">
    <block dot-block="titleContent">Some other Browser title</block>
    <block dot-block="mainContent">
        <div>Hello World!</div>
    </block>
</template>
```

Will output the content of mainLayout.html replaced by the content of someSite.html.

You can use multi-inheritance. The code in `home.html` is triggered block-wise. So only blocks that
are requested by `mainLayout.tpl.html` will trigger execution of the concrete blocks of `home.html`.



## Using JavaScript Controller bridge

You can now include the controller-logic direct in your html templates and call them by
async AJAX requests.

```
<body>
    <script language="JavaScript" src="dotJS.js"></script>
    <script language="JavaScript" remote="yes" controller="yes">

        CTRL.processMessage = function (name, surname) {
            DOT.db.query("INSERT INTO Subscription (name, surname) VALUES (?, ?)", name, surname).exec();
        }

    </script>


    <a href="javascript:CTRL.processMessage("Matthias", "Leuffen")">Click me</a>


</body>

```

## Using traditional url controller bridge

```
<a href="[[ DOT.link(self, "Matthias", "Leuffen") ]]">Click me</a>

```

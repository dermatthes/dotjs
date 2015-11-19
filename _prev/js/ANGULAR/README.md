# Using DOTJS Angular Bridge

## Activating the bridge

In your template just add

```

<body>
..
    <script remote="remote">
        DOT.include("dot://ANGULAR/ANGULAR.js");
        DOT.ANGULAR.printAngularInit();
    </script>
</body>

```

This will generate a brower-side stub. Please make sure to manually require the 
vendor Angular javascript code.

## Using the Bridge

You can access the observed object by using and modifying `$$observed` which should be
the last parameter of your Action.

```
<script remote>

    /* This is the SERVER - Side */
    CTRL.prototype.someAction = function (param1, param2, $$observed) {
        $$observed.someText = "You said: " + $$observed.textBox;
    }
</script>

<script language="JavaScript">
    /* Browser-Side */
    angular.module("SiteApp", ["$dotremote"]).controller("TestCtrl", function ($dotremote) {

        var remote = $dotremote.register(this);
        
        this.someAction = function () {
            remote.someAction("wurst");
        };
    });

</script>
```

You'll immediatly benefit from Client<>Server Code-Completion in your ide.

##




(function () {


    /**
     *
     * @param {DotRemote} dotRemote
     * @param {object} observedObject
     * @constructor
     */
    function CTRLProxy (dotRemote, observedObject, config, $log) {
        "use strict";
        var self = this;

        // Register all Actions to this object
        // On EcmaScript 6 we'll change this to use Proxy() class
        dotRemote.__call("getActions", {}, {})
            .then(function (response) {
                var actionArr = response.data;
                $log.debug("[DOTJS][ANGULAR][CTRLProxy] Loading available actions...");
                for(var i=0; i < actionArr.length; i++) {
                    var curActionName = actionArr[i];



                    self[curActionName] = function () {
                        var params = [ curActionName ];
                        for (var i2=0; i2<arguments.length; i2++) {
                            params.push(arguments[i2]);
                        }


                        return dotRemote.__call(params, observedObject, config)
                            .then(function (response) {
                                for (var key in response.data) {
                                    observedObject[key] = response.data[key];
                                }
                                return response;
                            });

                    }
                }
                $log.debug("[DOTJS][ANGULAR][CTRLProxy] Available remote actions:", actionArr);
                $log.debug("[DOTJS][ANGULAR][CTRLProxy] Successful registered on object:", observedObject);
            });
    }


    /**
     *
     * @param $http
     * @constructor
     */
    function DotRemote ($http, $q, $log) {
        "use strict";

        var mDefaultConfig = {
            exceptionHandler: function(response) {},    // Called if a {error: } field is in the result
            errorHandler: function(response) {},        // Called if result cannot be parsed as json
            loadHandler: function(response){},          //

            locking: "controller",              // none:        No locking
                                                // controller:  Only one request per controller
                                                // action:      Only one action per Controller

            onLock: "cancel",                   // cancel:      Cancel the running request and start a new on
                                                // spool:       Spool the request until the pending request finishes
                                                // reject:      Run the reject-action and keep the pending request running

            onReject: function () {},

            httpHeaderAjax:   "X-DotJs-IsAjaxRequest",  // Don't change this!

            method: "POST",
            url: null,

            ctrlPropertyFilter: function (name, data){return data;}     // Filter data of the controller with this function
        };


        /**
         *
         * @param action
         * @param data
         * @param config
         * @returns {$q}
         * @public
         */
        this.__call = function (action, data, config) {
            "use strict";

            if (typeof action === "undefined") {
                throw new Error("call(): Parameter 1 must be valid array or string");
            }
            if (typeof action === "string") {
                action = [ action ];
            }

            var curConfig = copyClone(mDefaultConfig);
            for (var key in config) {
                if (!config.hasOwnProperty(key))
                    continue;
                curConfig[key] = config[key];
            }


            if (curConfig.url === null) {
                curConfig.url = DOT.ENV.scriptName
            }

            if (curConfig.url.endsWith("/"))
                curConfig.url = curConfig.url.slice(0, -1); // Remove last /

            for (var i = 0; i < action.length; i++) {
                curConfig.url += "/" + encodeURI(encodeURI(action[i]));
            }


            var httpRequest = {
                method: curConfig.method,
                data: data,
                headers: {
                },
                url: curConfig.url
            };

            httpRequest.headers[curConfig.httpHeaderAjax] = "Be the force...";



            return $http(httpRequest);
        };



        /**
         * Register a angular controller as dot-remote
         *
         * @param angularCtrl
         * @param {{url: {string}}} config
         * @return {CTRL}
         */
        this.register = function (angularCtrl, config) {
            "use strict";

            var curConfig = mDefaultConfig;

            if (typeof config !== "undefined") {
                for (var key in config) {
                    curConfig[key] = config[key];
                }
            }

            return new CTRLProxy(this, angularCtrl, config, $log);

        };


    }


    var anguldot = angular.module("$dotremote", []);
    anguldot.factory("$dotremote", function ($http, $q, $log) {
        return new DotRemote($http, $q, $log);
    });

})();
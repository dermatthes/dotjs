/**
 *
 * @param $http
 * @constructor
 */
function DotRemote ($http, $q, $log) {
    "use strict";


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

        var curConfig = copyClone(config);

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

        var curConfig = copyClone(CONFIG_DEFAULT_ANGULAR_DOTREMOTE);

        if (typeof config !== "undefined") {
            for (var key in config) {
                curConfig[key] = config[key];
            }
        }

        return new CTRLProxy(this, angularCtrl, curConfig, $log);

    };


}



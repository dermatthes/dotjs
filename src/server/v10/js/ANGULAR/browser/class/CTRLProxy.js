

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
        dotRemote.__call("getActions", {}, config)
            .then(function (response) {
                var actionArr = response.data.returned;
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
                                if (typeof response.data !== "object") {
                                    $log.warn("[DOTJS][ANGULAR][CTRLProxy] Got non json formated result: '" + response.data);
                                }
                                if (typeof response.data.success === "undefined") {
                                    $log.warn("[DOTJS][ANGULAR][CTRLProxy] Invalid object format: DOTJS Angular expects return format: {success: bool, returned: {}, observed: {}}");
                                }
                                if (response.data.success === false) {
                                    $log.warn("[DOTJS][ANGULAR][CTRLProxy] Got exception on call: " + response.data.errorMsg);
                                    throw response.data.errorMsg;
                                }
                                $log.debug("[DOTJS][ANGULAR][CTRLProxy] Got response", response.data);
                                if (typeof response.data.observed !== "undefined") {
                                    for (var key in response.data.observed) {
                                        observedObject[key] = response.data.observed[key];
                                    }
                                }
                                return response.data.returned;
                            });

                    }
                }
                $log.debug("[DOTJS][ANGULAR][CTRLProxy] Available remote actions:", actionArr);
                $log.debug("[DOTJS][ANGULAR][CTRLProxy] Successful registered on object:", observedObject);
            });
    }



    /**
     *
     * @param {DotRemote} dotRemote
     * @param {object} observedObject
     * @constructor
     */
    function CTRLProxy (dotRemote, observedObject, config, $log) {
        "use strict";
        var self = this;

        this.className = "CTRLProxy";

        var _evaluateReturnOptions = function (returnOptions, lastCallPromise) {
            $log.debug("[DOT][ANGULAR][CTRLProxy] Evaluating returnOptions: ", returnOptions);
            if (typeof returnOptions.event === "object") {
                var event = returnOptions.event;
                if (returnOptions.preventDefault === true) {
                    $log.debug("[DOT][ANGULAR][CTRLProxy] preventDefault() (Browser)");
                    event.preventDefault();
                }
                if (returnOptions.stopPropagation === true) {
                    $log.debug("[DOT][ANGULAR][CTRLProxy] Stopping event propagation (Browser)");
                    event.stopPropagation();
                }

            }

            if (returnOptions.return === "promise")
                return lastCallPromise;

            if (typeof returnOptions.return === "function")
                return returnOptions.return();

            return returnOptions.return;
        };



        // Register all Actions to this object
        // On EcmaScript 6 we'll change this to use Proxy() class
        dotRemote.__call("getActions", {}, config)
            .then(function (response) {
                var actionArr = response.data.returned;
                $log.debug("[DOTJS][ANGULAR][CTRLProxy] Loading available actions...");
                for(var i=0; i < actionArr.length; i++) {
                    var curActionName = actionArr[i];



                    self[curActionName] = function () {
                        $log.debug("[DOTJS][ANGULAR][CTRLProxy] Action: '" + curActionName + "' called with:", arguments);

                        var params = [ curActionName ];
                        var returnOptions = {
                            return: "promise",
                            stopPropagation: false,
                            preventDefault: false,
                            event: null
                        };
                        for (var i2=0; i2<arguments.length; i2++) {
                            if (typeof arguments[i2] === "object") {
                                if (i2 > 0) {
                                    $log.warn("[DOTJS][ANGULAR][CTRLProxy] Action: '" + curActionName + "': Only first argument is allowed to be other than string (Browser script).");
                                    throw new Error("Action: '" + curActionName + "': Only first argument is allowed to be other than string (Browser script).");
                                }
                                var returnOptions = arguments[i2];
                                params.push("$$");
                                continue;
                            }
                            params.push(arguments[i2]);
                        }

                        // Collect data to transport as scope to client.
                        var objectData = {};
                        for (var name in observedObject) {
                            if (typeof observedObject[name] === "function")
                                continue;
                            if (typeof observedObject[name] === "object") {
                                if (observedObject[name].className === "CTRLProxy")
                                    continue;
                            }
                            objectData[name] = observedObject[name];
                        }


                        var promise = dotRemote.__call(params, objectData, config)
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

                        return _evaluateReturnOptions(returnOptions, promise);
                    }
                }
                $log.debug("[DOTJS][ANGULAR][CTRLProxy] Available remote actions:", actionArr);
                $log.debug("[DOTJS][ANGULAR][CTRLProxy] Successful registered on object:", observedObject);
            });
    }

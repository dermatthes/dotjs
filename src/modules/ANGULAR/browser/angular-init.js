


var anguldot = angular.module("$dotremote", []);
anguldot.factory("$dotremote", function ($http, $q, $log) {
    return new DotRemote($http, $q, $log);
});





var CONFIG_DEFAULT_ANGULAR_DOTREMOTE = {
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
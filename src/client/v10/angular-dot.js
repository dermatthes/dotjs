
(function () {


    /**
     *
     * @param $http
     * @constructor
     */
    function DotRemote ($http) {

        /**
         * Register a angular controller as dot-remote
         *
         * @param controllerInstance
         */
        this.register = function (controllerInstance) {

            controllerInstance.call = function (controller, $event, opt) {
                request = {
                    data: controllerInstance,
                    event: $event,
                    opt: opt
                };
                $http({
                    method: "POST",
                    url: "test.php?call=" + encodeURI(controller),
                    data: request
                }).then(function (response) {
                    console.log ("New data:", response);
                    for (var key in response.data) {
                        controllerInstance[key] = response.data[key];
                    }
                });
            }

        }

    }


    var anguldot = angular.module("$dotremote", []);
    anguldot.factory("$dotremote", function ($http) {
        return new DotRemote($http);
    });

})();
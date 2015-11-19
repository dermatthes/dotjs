

DOT.ANGULAR = {


};


/**
 * Overwrite default dispatcher Method
 */
DOT.dispatchAjaxRequest = function (action, params) {
    var response = {
        success: true
    };

    var ctrl = new CTRL();
    var curParams = getCallParams(ctrl[action], params,
        {
            $$observed: function () {
                var data = copyClone(DOT.REQUEST.json);

                response.observed = new Object();

                for (var key in data) {
                    response.observed[key] = copyClone(data[key]);
                }

                return response.observed;
            }
        });

    try {
        if (typeof ctrl[action] === "undefined")
            throw new Error("Called action '" + action + "' is not available on CTRL object.");
        var curReturn = ctrl[action].apply(ctrl, curParams);
        if (typeof curReturn !== "undefined")
            response.returned = curReturn;
        return JSON.stringify(response);
    } catch (ex) {
        response.success = false;
        response.errorMsg = ex;
        response.errorFn = ctrl[action].toString();
        return JSON.stringify(response);
    }
};


function getCallParams (fn, params, injections) {
    var re = /function\s+(.*?)\((.*?)\)/;
    var signature = fn.toString();
    var paramNames = signature.match(re)[2].split(/,\s*/);

    var paramI = 0;

    var actualParams = [];
    for (var i = 0; i < paramNames.length; i++) {
        var curParamName = paramNames[i];
        if (typeof injections[curParamName] !== "undefined") {
            if (typeof injections[curParamName] === "function") {
                actualParams.push(injections[curParamName](curParamName))
            } else {
                actualParams.push(injections[curParamName]);
            }
            continue;
        }
        actualParams.push(params[paramI++]);
    }
    return actualParams;
}
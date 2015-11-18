if (typeof String.prototype.endsWith !== 'function') {

    /**
     * Check if String ends with
     * 
     * @param suffix
     * @returns {boolean}
     */
    String.prototype.endsWith = function(suffix) {
        return this.indexOf(suffix, this.length - suffix.length) !== -1;
    };
}


/**
 * Method to disable write-protection of PHP-Objects and clone them
 *
 * @param obj
 * @returns {*}
 */
function copyClone(obj) {
    if(obj === null || typeof(obj) !== 'object' || 'isActiveClone' in obj)
        return obj;

    var temp = {};

    for(var key in obj) {
        if(Object.prototype.hasOwnProperty.call(obj, key)) {
            obj['isActiveClone'] = null;
            temp[key] = copyClone(obj[key]);
            delete obj['isActiveClone'];
        }
    }

    return temp;
}

/**
 * Returns stacktrace.
 * @returns {*}
 */
function stacktrace() {
    function st2(f) {
        return !f ? [] :
            st2(f.caller).concat([f.toString().split('(')[0].substring(9) + '(' + f.arguments.join(',') + ')']);
    }
    return st2(arguments.callee.caller);
}
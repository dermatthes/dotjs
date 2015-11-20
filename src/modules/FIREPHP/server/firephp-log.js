
/* overwrite the default null-logger from boot.js */


/**
 *
 * @param label
 * @constructor
 */
function FirePHPTableWriter (label) {


    var rowArr = [];


    /**
     *
     * @param headerArray
     * @returns {FirePHPTableWriter}
     */
    this.header = function (headerArray) {
        rowArr.unshift(headerArray);
        return this;
    };

    /**
     *
     * @param rowArray
     * @returns {FirePHPTableWriter}
     */
    this.row = function (rowArray) {
        rowArr.push(rowArray);
        return this;
    };

    /**
     *
     * @returns {null}
     */
    this.out = function () {
        DOT_BRIDGE.FIREPHP.TABLE(label, rowArr);
        return null;
    }

}



LOG = {
    warn: function (){
        DOT_BRIDGE.FIREPHP.LOG("warn", arguments);
    },
    info: function (){
        DOT_BRIDGE.FIREPHP.LOG("info", arguments);
    },
    debug: function () {
        DOT_BRIDGE.FIREPHP.LOG("log", arguments);
    },
    error: function (){
        DOT_BRIDGE.FIREPHP.LOG("error", arguments);
    },
    table: function (label, tableRowArray) {
        if (typeof tableRowArray === "undefined")
            tableRowArray = [];
        return new FirePHPTableWriter(label, tableRowArray);
    }
};
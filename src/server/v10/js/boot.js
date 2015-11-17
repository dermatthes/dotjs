/**
 * Created by matthes on 12.11.15.
 */


/**
 * All Block-Elements go here
 *
 * @type {function[]}
 */
var BLOCK = function(){};



/**
 * All Macros in Templates go here
 *
 * @type {function[]}
 */
var MACRO = function(){};

var __DOT = {};


/**
 * Method to disable write-protection of PHP-Objects
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


var DOT = {



    CTRL: {},

    /**
     * The original Request
     *
     * @type {{get: {}, post: {}, json: {data: {}, event:{}}, headers: {} }}
     */
    get REQUEST () {
        if (typeof __DOT.REQUEST === "undefined")
            __DOT.REQUEST = copyClone(DOT_BRIDGE.REQUEST.GET());
        return __DOT.REQUEST;
    },

    /**
     * Output string to outputBuffer
     *
     * @param stringToPrint
     */
    print: function (stringToPrint) {
        DOT_BRIDGE.OUT.OUT_PRINT(stringToPrint);
    },

    /**
     * Dump the data-structure
     *
     * @param data
     */
    dump: function (data) {
        DOT_BRIDGE.OUT.DUMP(data);
    },

    template: function (fileName) {
        DOT_BRIDGE.FS.USE_TEMPLATE(fileName);
    },

    /**
     * BASE: Include and execute a JS Script
     *
     * @param fileName
     */
    include: function (fileName) {
        DOT_BRIDGE.FS.FS_INCLUDE(fileName);
    },

    /**
     * Include a server-side extendsion located
     * beneath js/name/name2.js
     *
     * @param {String} name
     */
    extension: function (name) {
        DOT_BRIDGE.FS.USE_EXTENSION(name);
    },

    fileGetContents: function (fileName) {
        return DOT_BRIDGE.FS.FILE_GET_CONTENTS(fileName);
    }

};






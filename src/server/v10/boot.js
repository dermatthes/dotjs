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




var DOT = {

    /**
     * Output string to outputBuffer
     *
     * @param stringToPrint
     */
    print: function (stringToPrint) {
        DOT_BRIDGE.OUT_PRINT(stringToPrint);
    },

    template: function (fileName) {
        DOT_BRIDGE.FS_USE_TEMPLATE(fileName);
    },

    /**
     * BASE: Include and execute a JS Script
     *
     * @param fileName
     */
    require: function (fileName) {
        DOT_BRIDGE.FS_INCLUDE(fileName);
    },

    fileGetContents: function (fileName) {
        return DOT_BRIDGE.FS_FILE_GET_CONTENTS(fileName);
    }

};





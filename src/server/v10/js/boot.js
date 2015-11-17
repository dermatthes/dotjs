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


DOT_BRIDGE.OUT_PRINT("wurst");

var DOT = {

    /**
     * Output string to outputBuffer
     *
     * @param stringToPrint
     */
    print: function (stringToPrint) {
        DOT_BRIDGE.OUT.OUT_PRINT(stringToPrint);
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





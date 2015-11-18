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




/**
 * Local Controller
 *
 * register actions here using
 *
 * CTRL.prototype.<someAction> = function () {};
 *
 * @constructor
 */
var CTRL = function () {

    /**
     * Return a list of registered Actions
     *
     * @return {string[]}
     */
    this.getActions = function () {
        return Object.getOwnPropertyNames(CTRL.prototype).filter(function (name) { return typeof CTRL.prototype[name] === "function"; });
    }
};





var DOT = {
    __CACHE: {},



    /**
     * The original Request
     *
     * @type {{get: {}, post: {}, json: {data: {}, event:{}}, headers: {} }}
     */
    get REQUEST () {
        if (typeof DOT.__CACHE.REQUEST === "undefined")
            DOT.__CACHE.REQUEST = copyClone(DOT_BRIDGE.SERVER.REQUEST());
        return DOT.__CACHE.REQUEST;
    },

    /**
     *
     * @type {{requestMethod: string, queryString: string, self: string, scriptName: string, remoteAddr: string}}
     */
    get ENV () {
        if (typeof DOT.__CACHE.ENV === "undefined")
            DOT.__CACHE.ENV = DOT_BRIDGE.SERVER.ENV(); // Readonly
        return DOT.__CACHE.ENV;
    },


    /**
     * Called whenever a action is called
     *
     * Default behaviour: Call the action and return
     * the return value.
     *
     * @param action
     * @param params
     */
    dispatchAjaxRequest: function (action, params) {
        return JSON.stringify((new CTRL())[action].call(action, params));
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
     * BASE: Include actions from other template
     *
     * @param fileName
     */
    includeActions: function (fileName) {
        DOT_BRIDGE.FS.FS_INCLUDE_ACTIONS(fileName);
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








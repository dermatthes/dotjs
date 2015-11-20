/**
 * The client (browser) side of the DOTJS Framework.
 */

/* @var {console} LOG */
var LOG = console;
LOG.table = function (label, tableRowArr) {
    throw new Error("Not yet implemented on browser side");
};


var DOT = {

    /**
     * The original Request
     *
     * @type {{get: {}, post: {}, json: {data: {}, event:{}}, headers: {} }}
     */
    get REQUEST () {
        throw new Error("DOT.REQUEST only available on server side. **FOR SECURITY**");
    },

    /**
     *
     * @type {{requestMethod: string, queryString: string, self: string, scriptName: string, remoteAddr: string}}
     */
    get ENV () {
        return $$$ENV$$$;
    }, // MUST BE REPLACED JSON.stringify()

    /**
     * Output string to outputBuffer
     *
     * @param stringToPrint
     */
    print: function (stringToPrint) {
        console.log("DOT.print() called on browser-side: ",stringToPrint);
    },

    /**
     * Dump the data-structure
     *
     * @param data
     */
    dump: function (data) {
        console.log ("DOT.dump() on browser side: ", $data);
    },

    template: function (fileName) {
        throw new Error("DOT.template() not available on browser-side. **FOR SECURITY**");
    },

    /**
     * BASE: Include and execute a JS Script
     *
     * @param fileName
     */
    include: function (fileName) {
        throw new Error("DOT.include() not available on browser-side. **FOR SECURITY**");
    },

    /**
     * Include a server-side extendsion located
     * beneath js/name/name2.js
     *
     * @param {String} name
     */
    extension: function (name) {
        throw new Error("DOT.extension() not available on browser-side. **FOR SECURITY**");
    },

    fileGetContents: function (fileName) {
        throw new Error("DOT.fileGetContent() not available on browser-side. **FOR SECURITY**");
    }

};
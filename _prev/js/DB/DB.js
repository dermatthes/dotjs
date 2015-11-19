



DOT.DB = {
    __LINKID_INDEX: 0,
    __RESULTS: [],

    /**
     * Send SQL Query to Server
     *
     * @param stmt
     * @param params
     * @return {DbResult}
     */
    query: function (stmt, params) {
        var linkId = DOT.DB.__LINKID_INDEX++;
        var curResult = new DbResult(linkId, stmt, params);
        DOT.DB.__RESULTS[linkId] = curResult;
        return curResult;
    }


};

/**
 *
 * @param linkId
 * @param stmt
 * @param params
 * @constructor
 */
function DbResult (linkId, stmt, params) {
    var self = this;
    var mCallback = null;


    /**
     * Will be executed on every ResultSet from Server.
     *
     * @param data
     * @private
     */
    this.__lineIn = function (data) {
        if (mCallback !== null)
            mCallback(data);
    };

    /**
     *
     * @param callback
     * @returns {DbResult}
     */
    this.forEach = function (callback) {
        mCallback = callback;
        return this;
    };

    /**
     * Run the query on the server
     *
     * @returns {*}
     */
    this.exec = function () {
        var result = DOT_BRIDGE.DB.QUERY(linkId, stmt, params);
        delete DOT.DB.__RESULTS[linkId];
        return result;
    }

}




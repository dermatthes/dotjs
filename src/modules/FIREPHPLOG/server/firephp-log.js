
/* overwrite the default null-logger from boot.js */

LOG = {
    warn: function (){
        DOT_BRIDGE.FIREPHPLOG.LOG("warn", arguments);
    },
    info: function (){
        DOT_BRIDGE.FIREPHPLOG.LOG("info", arguments);
    },
    debug: function () {
        DOT_BRIDGE.FIREPHPLOG.LOG("log", arguments);
    },
    error: function (){
        DOT_BRIDGE.FIREPHPLOG.LOG("error", arguments);
    }
};
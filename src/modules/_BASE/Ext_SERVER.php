<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 17.11.15
 * Time: 18:19
 */



namespace dotjs\v10\modules\_BASE;


use dotjs\v10\server\core\LowLevelExtension;
use dotjs\v10\server\DotJsBridge;

class Ext_SERVER implements LowLevelExtension {

    const SECURE_HEADER_FORWARDED_FOR = "X-Forwarded-For";


    private $mAllowForwardedForHeader;

    public function __construct ($allowForwarededForHeader=false) {
        $this->mAllowForwardedForHeader = $allowForwarededForHeader;
    }


    public function REQUEST () {
        $req = [
            "post"  => $_POST,
            "get"   => $_GET,
            "json"  => null,
            "headers" => getallheaders()

        ];

        if (substr($req["headers"]["Content-Type"], 0, 16)  === "application/json") {
            $req["json"] = json_decode(file_get_contents("php://input"));
        }

        return $req;
    }


    public function ENV() {
        $response = [
            "requestMethod" => $_SERVER["REQUEST_METHOD"],
            "queryString"   => $_SERVER["QUERY_STRING"],
            "self"          => $_SERVER["PHP_SELF"],
            "scriptName"    => $_SERVER["SCRIPT_NAME"],
            "remoteAddr"    => $_SERVER["REMOTE_ADDR"]
        ];

        if ($this->mAllowForwardedForHeader && isset ($_SERVER["HTTP_" . strtoupper(self::SECURE_HEADER_FORWARDED_FOR)])) {
            $response["remoteAddr"] = $_SERVER["HTTP_" . strtoupper(self::SECURE_HEADER_FORWARDED_FOR)];
        }
        return $response;
    }

    /**
     * The name of the Feature
     *
     * @return string
     */
    public function getName() {
        return "SERVER";
    }


    /**
     * @var DotJsBridge
     */
    private $mDotJsBridge;

    /**
     * Method to call once, before module is activated
     *
     * @param $bridge DotJsBridge
     * @return bool
     */
    public function init(DotJsBridge $bridge) {
        $this->mDotJsBridge = $bridge;
    }

    /**
     * Return JavaScript code to send to the Browser
     *
     * @return string[]
     */
    public function getBrowserJsFiles() {
        return [];
    }

    /**
     * JavaScript Files to read from the Server side.
     *
     *
     * @return string[]
     */
    public function getServerJsFiles() {
        return [];
    }
}
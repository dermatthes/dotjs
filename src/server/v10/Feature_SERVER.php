<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 17.11.15
 * Time: 18:19
 */



namespace dotjs\server\v10;


use dotjs\server\v10\V8Wrapper;

class Feature_SERVER {

    const SECURE_HEADER_FORWARDED_FOR = "X-Forwarded-For";

    /**
     * @var V8Wrapper
     */
    private $mV8Wrapper;

    private $mAllowForwardedForHeader;

    public function __construct (V8Wrapper $v8wrapper, $allowForwarededForHeader=false) {
        $this->mV8Wrapper = $v8wrapper;
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


}
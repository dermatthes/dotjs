<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 17.11.15
 * Time: 18:19
 */



namespace dotjs\server\v10;


use dotjs\server\v10\V8Wrapper;

class Feature_REQUEST {

    /**
     * @var V8Wrapper
     */
    private $mV8Wrapper;

    public function __construct (V8Wrapper $v8wrapper) {
        $this->mV8Wrapper = $v8wrapper;
    }


    public function GET () {
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


}
<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 16.11.15
 * Time: 14:02
 */



namespace dotjs\server\v10\js\DB;


use dotjs\server\v10\V8Wrapper;

class Feature_DB {

    /**
     * @var V8Wrapper
     */
    private $mV8Wrapper;

    public function __construct (V8Wrapper $v8wrapper) {
        $this->mV8Wrapper = $v8wrapper;
    }


    public function QUERY ($resultIdentifier, $stmt, $params=[]) {
        for ($i=0; $i<10; $i++) {
            $this->mV8Wrapper->executeString("DOT.DB.__RESULTS[{$resultIdentifier}].__lineIn(". json_encode(["data"=>$i]) .");");
        }
        return true;
    }


}
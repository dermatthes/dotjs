<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 16.11.15
 * Time: 18:32
 */

namespace dotjs\v10\server\vendor;


class V8Wrapper {

    private $mV8;

    public function __construct () {
        $this->mV8 = new \V8Js("DOT_BRIDGE");
    }


    private $callTrace = [];


    public function executeString ($string, $identifier="V8Js::executeString()") {
        //echo "\n<br>V8-Executing:";
        //highlight_string($string);
        $this->callTrace[] = $string;
        return $this->mV8->executeString($string, $identifier);
    }


    public function registerCallback ($name, callable $callable) {
        $this->mV8->$name = $callable;
    }


    public function registerClass ($name, $object) {
        $this->mV8->$name = $object;
    }

}
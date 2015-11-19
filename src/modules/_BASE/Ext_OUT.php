<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 16.11.15
 * Time: 16:53
 */


namespace dotjs\v10\modules\_BASE;


use dotjs\v10\server\core\LowLevelExtension;
use dotjs\v10\server\DotJsBridge;

class Ext_OUT implements LowLevelExtension {

    public function __construct () {
    }


    private $mOutBuffer = "";

    private $mOmitPrint = FALSE;

    public function SET_OMIT_PRINT ($val) {
        $this->mOmitPrint = $val;
    }


    public function DUMP ($data) {
        echo "Dump request:";
        print_r($data);
    }


    /**
     * DOT_BRIDGE.FS_INCLUDE()
     * -> FS.include()
     *
     * @param $fileName
     */
    public function OUT_PRINT($string) {
        if ( ! $this->mOmitPrint)
            $this->mOutBuffer .= $string;
    }

    public function getOutBuffer() {
        return $this->mOutBuffer;
    }

    /**
     * The name of the Feature
     *
     * @return string
     */
    public function getName() {
        return "OUT";
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
<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 19.11.15
 * Time: 14:28
 */


namespace dotjs\v10\modules\FIREPHPLOG;


use dotjs\v10\server\core\LowLevelExtension;
use dotjs\v10\server\DotJsBridge;

class Ext_FIREPHPLOG implements LowLevelExtension {



    public function LOG ($level, $params) {

        switch ($level) {
            case "log":
                $this->mFirePhp->log($params);
                break;
            case "warn":
                $this->mFirePhp->warn($params);
                break;
            case "error":
                $this->mFirePhp->error($params);
                break;
            case "info":
                $this->mFirePhp->info($params);
                break;
        }

    }

    /**
     * The name of the Feature
     *
     * @return string
     */
    public function getName() {
        return "FIREPHPLOG";
    }


    /**
     * @var DotJsBridge
     */
    private $mDotJsBridge;

    /**
     * @var \FirePHP
     */
    private $mFirePhp;

    /**
     * Method to call once, before module is activated
     *
     * @param $bridge DotJsBridge
     * @return bool
     */
    public function init(DotJsBridge $bridge) {
        require_once(__DIR__ . "/vendor/FirePHP.php");
        $this->mDotJsBridge = $bridge;
        $this->mFirePhp = new \FirePHP();
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
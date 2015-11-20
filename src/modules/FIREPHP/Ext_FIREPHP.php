<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 19.11.15
 * Time: 14:28
 */


namespace dotjs\v10\modules\FIREPHP;


use dotjs\v10\server\core\LowLevelExtension;
use dotjs\v10\server\DotJsBridge;

class Ext_FIREPHP implements LowLevelExtension {



    public function LOG ($level, $params) {

        $data = [];
        foreach ($params as $curParam) {
            $data[] = $curParam;
        }

        switch ($level) {
            case "log":
                $this->mFirePhp->log($data);
                break;
            case "warn":
                $this->mFirePhp->warn($data);
                break;
            case "error":
                $this->mFirePhp->error($data);
                break;
            case "info":
                $this->mFirePhp->info($data);
                break;
        }

    }


    public function TABLE ($label, $tableDataArr) {
        $this->mFirePhp->table($label, $tableDataArr);
    }

    /**
     * The name of the Feature
     *
     * @return string
     */
    public function getName() {
        return "FIREPHP";
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

        $bridge->setLogger(new FirePhpDotLogger($this->mFirePhp, "[DOT-SERVER]"));
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
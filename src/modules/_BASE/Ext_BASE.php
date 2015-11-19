<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 19.11.15
 * Time: 11:19
 */

    namespace dotjs\v10\modules\_BASE;


    use dotjs\v10\server\DotJsBridge;
    use dotjs\v10\server\core\LowLevelExtension;

    class Ext_BASE implements LowLevelExtension {

        /**
         * The name of the Feature
         *
         * @return string
         */
        public function getName() {
            return "BASE";
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
         * @return string
         */
        public function getBrowserJsFiles() {
            return [
                __DIR__ . "/browser/dot.js",
                __DIR__ . "/shared/toolkit.js"
            ];
        }

        /**
         * JavaScript Files to read from the Server side.
         *
         *
         * @return string[]
         */
        public function getServerJsFiles() {
            return [
                __DIR__ . "/shared/toolkit.js",
                __DIR__ . "/server/boot.js"
            ];
        }
    }
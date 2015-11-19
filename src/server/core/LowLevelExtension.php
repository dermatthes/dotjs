<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 19.11.15
 * Time: 10:25
 */

    namespace dotjs\v10\server\core;




    use dotjs\v10\server\DotJsBridge;

    interface LowLevelExtension {

        /**
         * The name of the Feature
         *
         * @return string
         */
        public function getName();


        /**
         * Method to call once, before module is activated
         *
         * @param $bridge DotJsBridge
         * @return bool
         */
        public function init(DotJsBridge $bridge);

        /**
         * Return JavaScript code to send to the Browser
         *
         *
         *
         * @return string[]|callable[]
         */
        public function getBrowserJsFiles();

        /**
         * JavaScript Files to read from the Server side.
         *
         *
         * @return string[]
         */
        public function getServerJsFiles ();

    }
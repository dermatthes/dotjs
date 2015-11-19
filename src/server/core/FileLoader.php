<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 16.11.15
 * Time: 14:38
 */


    namespace dotjs\v10\server\core;


    interface FileLoader {


        /**
         * Sets the base path for the 'dot://' directory
         *
         * @param $dir
         * @return mixed
         */
        public function setExtensionRoot ($dir);

        /**
         * Load the contents required by DOT.fileGetContents(fileName) and DOT.require(fileName) and DOT.run(fileName)
         *
         * @param $fileName
         * @return mixed
         */
        public function getContents($fileName);

    }


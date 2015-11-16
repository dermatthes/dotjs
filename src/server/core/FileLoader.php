<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 16.11.15
 * Time: 14:38
 */


    namespace dotjs\server\core;


    interface FileLoader {

        /**
         * Load the contents required by DOT.fileGetContents(fileName) and DOT.require(fileName) and DOT.run(fileName)
         *
         * @param $fileName
         * @return mixed
         */
        public function getContents($fileName);

    }


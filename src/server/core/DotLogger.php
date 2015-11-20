<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 20.11.15
 * Time: 15:24
 */

    namespace dotjs\v10\server\core;


    interface DotLogger {

        public function debug($message);

        public function warn ($message);

        public function error ($message);

        public function trace ($objectData);

    }
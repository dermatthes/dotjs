<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 20.11.15
 * Time: 15:27
 */

    namespace dotjs\v10\server\core;

    class DotNullLogger implements DotLogger {

        public function debug($message) {
            // Do nothing
        }

        public function warn($message) {
            // Do nothing
        }

        public function error($message) {
            // Do nothing
        }

        public function trace($objectData) {
            // Do nothing
        }
    }
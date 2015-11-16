<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 16.11.15
 * Time: 14:05
 */



    namespace dotjs\server\core;


    interface Bridge {


        /**
         * Build the ServerSide Runner environment
         *
         * @return \V8Js
         */
        public function buildRunner ();


        /**
         * @return \V8Js
         */
        public function buildRenderer ();

    }
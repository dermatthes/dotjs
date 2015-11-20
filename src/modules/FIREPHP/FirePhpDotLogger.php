<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 20.11.15
 * Time: 15:29
 */


    namespace dotjs\v10\modules\FIREPHP;


    use dotjs\v10\server\core\DotLogger;

    class FirePhpDotLogger implements  DotLogger {

        /**
         * @var \FirePHP
         */
        private $mFirePhp;

        private $mLabel;

        public function __construct (\FirePHP $firePhp, $label) {
            $this->mFirePhp = $firePhp;
            $this->mLabel = $label;
        }


        public function debug($message) {
            $this->mFirePhp->log($message, $this->mLabel);
        }

        public function warn($message) {
            $this->mFirePhp->warn($message, $this->mLabel);
        }

        public function error($message) {
            $this->mFirePhp->error($message, $this->mLabel);
        }

        public function trace($objectData) {
            $this->mFirePhp->trace();
        }
    }
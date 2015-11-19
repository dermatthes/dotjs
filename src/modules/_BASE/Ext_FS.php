<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 16.11.15
 * Time: 16:15
 */


    namespace dotjs\v10\modules\_BASE;



    use dotjs\v10\server\DotJsBridge;
    use dotjs\v10\server\core\LowLevelExtension;
    use dotjs\v10\template\TemplateControllerExtractor;

    class Ext_FS implements LowLevelExtension {

        /**
         * Include the actions from the specified template
         *
         * @param $fileName
         */
        public function FS_INCLUDE_ACTIONS($fileName) {
            $v8w = $this->mDotJsBridge->getV8Wrapper();
            $fileLoader = $this->mDotJsBridge->getFileLoader();

            // Overwrite the __MAIN - BLock of original Template
            $code ="(function(){\n";
            $code .= "\tvar __DIR__ = '" . dirname($fileName) . "';\n";
            $code .= "\tvar __FILE__ = '" . $fileName . "';\n";
            $extractor = new TemplateControllerExtractor();
            $code .= $extractor->parse($fileLoader->getContents($fileName));
            $code .= "})();\n";
            $v8w->executeString($code, $fileName);
        }

        /**
         * DOT_BRIDGE.FS_INCLUDE()
         * -> FS.include()
         *
         * @param $fileName
         */
        public function FS_INCLUDE($fileName) {
            $v8w = $this->mDotJsBridge->getV8Wrapper();
            $fileLoader = $this->mDotJsBridge->getFileLoader();

            $code ="(function(){\n";
            $code .= "\tvar __DIR__ = '" . dirname($fileName) . "';\n";
            $code .= "\tvar __FILE__ = '" . $fileName . "';\n";
            $code .= $fileLoader->getContents($fileName);
            $code .= "})();\n";
            try {
                $v8w->executeString($code, $fileName);
            } catch (\Exception $e) {
                throw new \Exception("Exception loading $fileName");
            }
        }



        public function FILE_GET_CONTENTS ($fileName) {
            return $this->mDotJsBridge->getFileLoader()->getContents($fileName);
        }


        private $mNextTemplate = NULL;


        public function USE_TEMPLATE ($fileName) {
            if ($this->mNextTemplate !== NULL)
                throw new \InvalidArgumentException("You cannot extend to more than one template. Use useTemplate only once per template");
            $this->mNextTemplate = $fileName;
        }

        public function getNextTemplate() {
            return $this->mNextTemplate;
        }

        public function clearNextTemplate() {
            $this->mNextTemplate = NULL;
        }


        /**
         * The name of the Feature
         *
         * @return string
         */
        public function getName() {
            return "FS";
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
<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 16.11.15
 * Time: 16:15
 */


    namespace dotjs\server\v10;


    use dotjs\server\core\FileLoader;
    use dotjs\template\TemplateControllerExtractor;
    use dotjs\template\TemplateParser;

    class Feature_FS {

        /**
         * @var V8Wrapper
         */
        private $mV8;

        /**
         * @var FileLoader
         */
        private $mFileLoader;

        /**
         * @var TemplateParser
         */
        private $mParser;

        public function __construct (V8Wrapper $v8, FileLoader $fileLoader, TemplateParser $parser) {
            $this->mV8 = $v8;
            $this->mFileLoader = $fileLoader;
            $this->mParser = $parser;
        }


        /**
         * Include the actions from the specified template
         *
         * @param $fileName
         */
        public function FS_INCLUDE_ACTIONS($fileName) {
            // Overwrite the __MAIN - BLock of original Template
            $code ="(function(){\n";
            $code .= "\tvar __DIR__ = '" . dirname($fileName) . "';\n";
            $code .= "\tvar __FILE__ = '" . $fileName . "';\n";
            $extractor = new TemplateControllerExtractor();
            $code .= $extractor->parse($this->mFileLoader->getContents($fileName));
            $code .= "})();\n";
            $this->mV8->executeString($code);
        }

        /**
         * DOT_BRIDGE.FS_INCLUDE()
         * -> FS.include()
         *
         * @param $fileName
         */
        public function FS_INCLUDE($fileName) {
            $code ="(function(){\n";
            $code .= "\tvar __DIR__ = '" . dirname($fileName) . "';\n";
            $code .= "\tvar __FILE__ = '" . $fileName . "';\n";
            $code .= $this->mFileLoader->getContents($fileName);
            $code .= "})();\n";
            $this->mV8->executeString($code);
        }



        public function FILE_GET_CONTENTS ($fileName) {
            return $this->mFileLoader->getContents($fileName);
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


    }
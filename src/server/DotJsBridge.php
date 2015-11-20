<?php
use dotjs\v10\modules\_BASE\Ext_FS;

/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 16.11.15
 * Time: 14:02
 */



    namespace dotjs\v10\server;



    use dotjs\v10\modules\_BASE\Ext_OUT;
    use dotjs\v10\modules\_BASE\Ext_SERVER;
    use dotjs\v10\server\core\DotLogger;
    use dotjs\v10\server\core\DotNullLogger;
    use dotjs\v10\server\core\FileLoader;
    use dotjs\v10\modules\_BASE\Ext_BASE;
    use dotjs\v10\modules\_BASE\Ext_FS;
    use dotjs\v10\server\core\LowLevelExtension;
    use dotjs\v10\server\js\DB\Feature_DB;

    use dotjs\v10\server\vendor\V8Wrapper;
    use dotjs\v10\template\TargetLanguageJavaScript;
    use dotjs\v10\template\TemplateControllerExtractor;
    use dotjs\v10\template\TemplateParser;

    class DotJsBridge {

        /**
         * @var V8Wrapper
         */
        private $mV8;
        /**
         * @var TemplateParser
         */
        private $mParser;

        /**
         * @var FileLoader
         */
        private $mFileLoader;


        /**
         * @var DotLogger
         */
        private $mLogger;


        public function __construct (FileLoader $loader) {
            $this->mLogger = new DotNullLogger();
            $this->mFileLoader = $loader;
            $this->mTemplateParser = new TemplateParser(new TargetLanguageJavaScript());

            $loader->setExtensionRoot(__DIR__ . "/../modules/");

            $this->addLowLevelExtension(new Ext_BASE());
            $this->addLowLevelExtension(new Ext_FS());
            $this->addLowLevelExtension(new Ext_OUT());
            $this->addLowLevelExtension(new Ext_SERVER());

            $this->setAutoStartLowLevelExtensions(["BASE", "OUT", "FS", "SERVER"]);
            $this->mV8 = new V8Wrapper();
        }


        /**
         * @return V8Wrapper
         */
        public function getV8Runner() {
            return $this->mV8;
        }

        /**
         * @param V8Wrapper $wrapper
         */
        public function setV8Runner(V8Wrapper $wrapper) {
            throw new \InvalidArgumentException("There is no reason for changing the V8Wrapper yet. This stub is only for compatibility");
        }


        /**
         * @return DotLogger
         */
        public function getLogger () {
            return $this->mLogger;
        }

        /**
         * @param DotLogger $logger
         */
        public function setLogger(DotLogger $logger) {
            $this->mLogger = $logger;
        }


        public function getFileLoader () {
            return $this->mFileLoader;
        }

        public function setFileLoader (FileLoader $fileLoader) {
            $this->mFileLoader = $fileLoader;
        }


        /**
         * @var LowLevelExtension[]
         */
        private $mLowLevelExtensions = [];

        /**
         * Register a server-side Feature
         *
         * @param LowLevelExtension $feature
         */
        public function addLowLevelExtension(LowLevelExtension $feature) {
            $this->mLowLevelExtensions[$feature->getName()] = $feature;
        }


        private $mAutoStartLowLevelExtensions = [];

        /**
         * Which ServerSide Features should be activated on startup
         *
         * @param array $featureNames
         */
        public function setAutoStartLowLevelExtensions (array $featureNames) {
            $this->mAutoStartLowLevelExtensions = $featureNames;
        }


        /**
         * @param $featureName
         */
        public function addAutoStartLowLevelExtension ($featureName) {
            $this->mAutoStartLowLevelExtensions[] = $featureName;
        }

        private $mTemplateParser;

        public function setTemplateParser (TemplateParser $templateParser) {
            $this->mTemplateParser = $templateParser;
        }

        public function getTemplateParser () {
            return $this->mTemplateParser;
        }


        /**
         * Build the ServerSide Runner environment
         *
         * @return V8Wrapper
         */
        public function buildRunner() {
            return $this->mV8;
        }


        /**
         * @return V8Wrapper
         */
        public function getV8Wrapper () {
            return $this->mV8;
        }



        public function getOutput () {
            $out = $this->getExtension("OUT");
            /* @var $out Ext_OUT */
            return $out->getOutBuffer();
        }

        private $mIsInitialized = FALSE;



        private $mInitializedLowLevelExtensions = [];

        public function getExtension ($name) {
            if ( ! isset ($this->mLowLevelExtensions[$name]))
                throw new \InvalidArgumentException("No LowLevelExtension with name '$name' registred");

            if ( ! isset ($this->mInitializedLowLevelExtensions[$name])) {
                $ext = $this->mLowLevelExtensions[$name];
                $ext->init($this);
                $this->mV8->registerClass($name, $ext);

                foreach ($ext->getServerJsFiles() as $fileName) {
                    $code = file_get_contents($fileName);
                    if ($code === NULL)
                        throw new \Exception("Cannot inject library code from file '$fileName'");
                    $this->mV8->executeString($code, $fileName);
                }

                foreach ($ext->getBrowserJsFiles() as $fileNameOrCallback) {
                    if (is_string($fileNameOrCallback)) {
                        $fileData = file_get_contents($fileNameOrCallback);
                        if ($fileData === NULL)
                            throw new \Exception("Cannot load browser library code from file '$fileNameOrCallback'");
                        $this->mV8->executeString("DOT.addBrowserLibraryCode('" . str_replace("\n", '\n', addslashes(file_get_contents($fileNameOrCallback))) . "');");
                    }
                }

                $this->mInitializedLowLevelExtensions[$name] = $ext;
            }
            return $this->mInitializedLowLevelExtensions[$name];
        }

        private function init () {
            if ($this->mIsInitialized === TRUE)
                return;
            $this->mIsInitialized = TRUE;

            foreach ($this->mAutoStartLowLevelExtensions as $curExtName) {
                $this->getExtension($curExtName);
            }

        }

        public function runAction ($templateName, $action, $params=[]) {
            $this->init();

            $extractor = new TemplateControllerExtractor();
            $jsCode = $extractor->parse($this->mFileLoader->getContents($templateName));

            try {
                $this->mV8->executeString($jsCode);
            } catch (\V8JsException $e) {
                $this->mLogger->error("Exeption runAction('$templateName', '$action'): {$e->getMessage()} ");
                throw $e;
            }

            $action = addslashes($action);


            try {
                $jsCode = "DOT.dispatchAjaxRequest('$action', " . json_encode($params) . ")";
                $data = $this->mV8->executeString($jsCode, $jsCode);
            } catch (\V8JsException $e) {
                $this->mLogger->error("Exeption when calling DOT.dispatchAjaxRequest(): {$e->getMessage()} ");
                throw $e;
            }
            echo $data;
        }


        public function runTemplate ($name) {
            $this->init();

            $fs = $this->getExtension("FS");
            /* @var $fs Ext_FS */


            try {
               $fs->FS_INCLUDE_TEMPLATE($name);
            } catch (\V8JsException $e) {
                $this->mLogger->error("Exeption in Template '$name': {$e->getMessage()} ");
                throw $e;
            }



            /* @var $fs Ext_FS */

            // Run the Templates
            while (($nextTemplate = $fs->getNextTemplate()) !== NULL) {
                $fs->clearNextTemplate();

                try {
                    $fs->FS_INCLUDE_TEMPLATE($nextTemplate);
                } catch (\V8JsException $e) {
                    $this->mLogger->error("Exeption in Template '$nextTemplate': {$e->getMessage()} ");
                    throw $e;
                }
            }

        }

    }
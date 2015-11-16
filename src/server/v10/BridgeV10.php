<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 16.11.15
 * Time: 14:02
 */



    namespace dotjs\server\v10;



    use dotjs\server\core\Bridge;
    use dotjs\server\core\FileLoader;
    use dotjs\template\TargetLanguageJavaScript;
    use dotjs\template\TemplateParser;

    class BridgeV10 implements Bridge {

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
         * @var Feature_OUT
         */
        private $mFeatureOut;

        /**
         * @var Feature_FS
         */
        private $mFeatureFs;

        public function __construct (FileLoader $loader) {
            $v8 = new V8Wrapper();
            //$v8->registerExtension("bootv10", file_get_contents(__DIR__ . "/boot.js"), array(), true);

            $v8->executeString(file_get_contents(__DIR__ . "/boot.js"));

            $this->mFileLoader = $loader;
            $this->mParser = new TemplateParser(new TargetLanguageJavaScript());

            $extensionClasses = [
                $this->mFeatureOut = new Feature_OUT(),
                $this->mFeatureFs = new Feature_FS($v8, $loader, $this->mParser)
            ];





            $this->mV8 = $v8;



            foreach ($extensionClasses as $curObj) {
                $reflection = new \ReflectionObject($curObj);
                foreach ($reflection->getMethods() as $method) {
                    if ( ! $method->isPublic())
                        continue;
                    $fnName = $method->getName();
                    $v8->registerCallback($fnName, function () use ($curObj, $fnName) {
                        return call_user_func_array([$curObj, $fnName], func_get_args());
                    });
                }
            }


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



        public function buildRenderer () {

        }

        public function getOutput () {
            return $this->mFeatureOut->getOutBuffer();
        }


        public function runTemplate ($name) {
            $code = $this->mParser->parse($this->mFileLoader->getContents($name));

            try {
                $this->mV8->executeString($code);
            } catch (\V8JsException $e) {
                echo "Fehler in: $code";
                throw $e;
            }


            // Run the Templates
            while (($nextTemplate = $this->mFeatureFs->getNextTemplate()) !== NULL) {
                $this->mFeatureFs->clearNextTemplate();
                $code = $this->mParser->parse($this->mFileLoader->getContents($nextTemplate));
                $this->mV8->executeString($code);

            }

        }

    }
<?php




    namespace dotjs\plugins;

    use dotjs\parser\XmlAttributeBag;
    use dotjs\parser\XmlRewriter;
    use dotjs\parser\XmlRewriterPlugin;

    class DotJsRepeatPlugin implements XmlRewriterPlugin {

        /**
         * @var \V8Js
         */
        private $mV8;

        public function __construct (\V8Js $v8) {
            $this->mV8 = $v8;
        }


        public function register(XmlRewriter $rewriter) {
           $rewriter->setElementCallback("script", [$this, "elem_script_atRemote"]);
        }


        public function elem_script_atRemote (\XMLReader $reader, \XMLWriter $writer) {
            if ($reader->getAttribute("dot-remote") === NULL)
                return false; // Continue processing
            $writer->writeRaw($this->mV8->executeString($reader->readInnerXml()));
            return true;
        }
    }


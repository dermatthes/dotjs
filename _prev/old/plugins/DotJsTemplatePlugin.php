<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.11.15
 * Time: 17:14
 */


    namespace dotjs\plugins;



    use dotjs\parser\XmlAttributeBag;
    use dotjs\parser\XmlRewriter;
    use dotjs\parser\XmlRewriterPlugin;

    class DotJsTemplatePlugin implements  XmlRewriterPlugin {


        private $mV8;

        /**
         * @var \XMLWriter
         */
        private $mOutputWriter;

        public function __construct (\V8Js $v8, \XMLWriter $outputWriter) {
            $this->mV8 = $v8;
            $this->mOutputWriter = $outputWriter;
        }


        public function register(XmlRewriter $rewriter) {
            $rewriter->setAttributeCallback("dot-for", [$this, "attr_dotfor"]);
        }


        public function attr_dotfor (\XMLReader $reader, \XMLWriter $writer, XmlAttributeBag $attr, $tagName) {
            $forStmt = $attr->getAttr("dot-for");
            $outer = $reader->readOuterXml();

            $inner = $reader->readInnerXml();

            $code  = "for($forStmt){\n";


            $innerRewriter = new XmlRewriter();
            $innerRewriter->getXmlReader()->xml($inner);
            $innerRewriter->getXmlWriter()->openMemory();

            $innerRewriter->addPlugin($this);




            foreach (explode("\n", $outer) as $curLine) {
                $code .= "\tPHP.print(\"" . addslashes($curLine) . "\\n\");\n";
            }




            $code .= "};\n";
            echo "Template-Code:" . highlight_string($code);
            $this->mV8->executeString($code);

        }

    }
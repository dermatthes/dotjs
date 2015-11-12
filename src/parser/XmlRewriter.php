<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.11.15
 * Time: 13:56
 */


    namespace dotjs\parser;

    class XmlRewriter {

        /**
         * @var \XMLWriter
         */
        private $mWriter;

        /**
         * @var \XMLReader
         */
        private $mReader;

        public function __construct (\XMLReader $reader = NULL, \XMLWriter $writer = NULL) {
            if ($writer === NULL)
                $writer = new \XMLWriter();
            $this->mWriter = $writer;

            if ($reader === NULL)
                $reader = new \XMLReader();
            $this->mReader = $reader;
        }


        /**
         * @return \XMLWriter
         */
        public function getXmlWriter () {
            return $this->mWriter;
        }


        /**
         * @return \XMLReader
         */
        public function getXmlReader () {
            return $this->mReader;
        }


        private $mCallbackElement = [];
        private $mCallbackAttribute = [];


        public function setElementCallback ($elementName, callable $callback) {
            $this->mCallbackElement[$elementName] = $callback;
        }


        public function setAttributeCallback ($attributeName, callable $callback) {
            $this->mCallbackAttribute[$attributeName] = $callback;
        }


        public function addPlugin (XmlRewriterPlugin $plutin) {
            $plutin->register($this);
        }

        private function _readBehindCurrentNode (\XMLReader $reader) {
            $depth = 1;
            while ($reader->read()) {
                if ($reader->nodeType == \XMLReader::ELEMENT)
                    $depth++;
                if ($reader->nodeType == \XMLReader::END_ELEMENT)
                    $depth--;
                if ($depth === 0)
                    break;
            }
        }


        private function _rewriteElement (\XMLReader $reader, \XMLWriter $writer) {
            $skipNode = false;
            if (isset ($this->mCallbackElement[$reader->name])) {
                $fn = $this->mCallbackElement[$reader->name];
                if (true === $fn ($reader, $writer))
                    $skipNode = true;
            }

            $origElement = [$reader->prefix, $reader->localName, $reader->namespaceURI];
            $origElementIsEmptyElement = $reader->isEmptyElement;
            $attributeBag = new XmlAttributeBag();


            $attribFn = [];
            if ($reader->hasAttributes) {
                while ($reader->moveToNextAttribute()) {
                    $attributeBag->injectAttribute($reader->name, $reader->value);
                    if (isset ($this->mCallbackAttribute[$reader->name])) {
                        $attribFn[] = $this->mCallbackAttribute[$reader->name];
                    }
                }
            }


            foreach ($attribFn as $curFn) {
                if (true === $curFn($reader, $writer, $attributeBag, $origElement[1])) {
                    $skipNode = true;
                }
            }


            if ( ! $skipNode) {
                if ($origElement[0] == "" && $origElement[2] == "") {
                    $writer->startElement($origElement[1]);
                } else {
                    $writer->startElementNs($origElement[0], $origElement[1], $origElement[2]);
                }
                foreach ($attributeBag->getAttributesArr() as $curAttr) {
                    $writer->writeAttribute($curAttr[0], $curAttr[1]);
                }
            }
            if ($origElementIsEmptyElement) {
                //$reader->read();
                // $writer->fullEndElement(); // <-- Funktioniert nicht
            }
            if ( ! $origElementIsEmptyElement && $skipNode) {
                $this->_readBehindCurrentNode($reader);
            }
        }


        public function rewrite () {
            $writer = $this->mWriter;
            $reader = $this->mReader;
            while ($reader->read()) {

                echo "\n<br>type={$reader->nodeType} name={$reader->name} value={$reader->value} prefix={$reader->prefix} namesapceURI={$reader->namespaceURI} localName={$reader->localName}";
                switch ($reader->nodeType) {

                    case \XMLReader::ELEMENT:
                        $this->_rewriteElement($reader, $writer); // <-- All magic is done here
                        break;

                    case \XMLReader::TEXT:
                        $writer->writeRaw($reader->value);
                        break;

                    case \XMLReader::WHITESPACE:
                        $writer->writeRaw($reader->value);
                        break;

                    case \XMLReader::SIGNIFICANT_WHITESPACE:
                        $writer->writeRaw($reader->value);
                        break;

                    case \XMLReader::CDATA:
                        $writer->writeCdata($reader->value);
                        break;

                    case \XMLReader::ENTITY_REF:
                        $writer->writeDtd($reader->name);
                        //  writer.WriteEntityRef(reader.Name);

                        break;

                    case \XMLReader::XML_DECLARATION:
                        break;
                    /*
                                case XmlNodeType.ProcessingInstruction:

                                      //writer.WriteProcessingInstruction( reader.Name, reader.Value );

                                      break;*/

                    case \XMLReader::DOC_TYPE:
                        $writer->writeDtd($reader->name, $reader->getAttribute("PUBLIC"), $reader->getAttribute("SYSTEM"), $reader->value);
                        break;

                    case \XMLReader::COMMENT:
                        $writer->writeComment($reader->value);
                        break;

                    case \XMLReader::END_ELEMENT:
                        echo "END ELEMEIT";
                        $writer->fullEndElement();
                        break;

                    default:
                        echo "NO PARSER";
                        break;
                }

            }
        }



    }


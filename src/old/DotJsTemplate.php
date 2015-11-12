<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.11.15
 * Time: 09:33
 */



    namespace dotjs\parser;


    class DotJsTemplate {


        private $mV8;

        /**
         * @var XmlCallbacks
         */
        private $mXmlCallbacks;

        /**
         * @var \XmlWriter
         */
        private $mXmlWriter;

        public function __construct () {
            $this->mV8 = new \V8Js();

            $this->mXmlWriter = new \XMLWriter();
            $this->mXmlWriter->openMemory();
            $this->mXmlCallbacks = new XmlCallbacks($this->mXmlWriter);
        }


        public function processUnparsed (SimpleXMLReader $reader) {
            $writer = $this->mXmlWriter;
            echo "\n<br>type={$reader->nodeType} name={$reader->name} value={$reader->value} prefix={$reader->prefix} namesapceURI={$reader->namespaceURI} localName={$reader->localName}";
            switch ($reader->nodeType) {

                case \XMLReader::ELEMENT:
                    if ($reader->prefix == "" && $reader->namespaceURI == "") {
                        $writer->startElement($reader->localName);
                    } else {
                        $writer->startElementNs($reader->prefix, $reader->localName, $reader->namespaceURI);
                    }


                    if ($reader->hasAttributes) {

                    }


                    //writer.WriteAttributes( reader, true );
                    if ($reader->isEmptyElement) {
                        $writer->endElement();
                    }
                    break;

                case \XMLReader::ATTRIBUTE:
                    if ($reader->prefix == "") {
                        $writer->writeAttribute($reader->name, $reader->value);
                    } else {
                        $writer->writeAttributeNs($reader->prefix, $reader->name, $reader->namespaceURI, $reader->value);
                    }
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
                    $writer->fullEndElement();
                    break;

                default:
                    echo "NO PARSER";
                    break;
            }

            return true;
        }


        public function parse ($inputData) {
            $reader = new SimpleXMLReader();
            $xmlCallback = $this->mXmlCallbacks;
            $reader->registerCallback("script", [$xmlCallback, "script"]);
            $reader->registerCallback("[@dot-repeat]", [$xmlCallback, "atDotRepeat"], $reader::ATTRIBUTE);
            $reader->registerCallback("*", [$this, "processUnparsed"], $reader::ANYNODE_UNPARSED);

            $reader->xml($inputData);
            $reader->parse();

            return $this->mXmlWriter->outputMemory();
        }










    }



<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.11.15
 * Time: 18:24
 */

    namespace dotjs\template;

    class TemplateParser {


        private $mJsHead = NULL;
        private $mJsFoot = NULL;

        public function isBlockElement (\XMLReader $reader) {
            $dotFor = $reader->getAttribute("dot-for");
            if ($dotFor !== NULL) {
                $this->mJsHead = "for({$dotFor}){";
                $this->mJsFoot = "};";
                return true;
            }
            return false;
        }

        /**
         * @var TemplateParser
         */
        private $mParent;

        public function setParent (TemplateParser $parent) {
            $this->mParent = $parent;
        }


        private $mChildContent = NULL;

        public function setChildContent ($content) {
            $this->mChildContent = $content;
        }



        public function parse (\XMLReader $reader, $readerDepth = 0) {
            $writer = new \XMLWriter();
            $writer->openMemory();

            $preContent = "";

            $childContent = "";

            $depth = 0;
            while ($reader->read()) {
                echo "\n<br>R:{$readerDepth} D:$depth type={$reader->nodeType} name={$reader->name} value={$reader->value} prefix={$reader->prefix} namesapceURI={$reader->namespaceURI} localName={$reader->localName}";

                switch ($reader->nodeType) {
                    case \XMLReader::ELEMENT:

                        $writer->startElement($reader->name);

                        if ( ! $reader->isEmptyElement)
                            $depth++;
                        echo "OPEN: $depth";
                        if ($this->isBlockElement($reader)) {
                            echo "<br>isblock";
                            $preContent = $writer->outputMemory(true); // Save and flush
                            echo "\nPre-Content: ";
                            highlight_string($preContent);

                            $childParser = new self();
                            $childParser->setParent($this);
                            $childContent = $childParser->parse($reader, $readerDepth+1);
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
                        $depth--;
                        echo "END Depth: $depth";
                        if ($depth <= 1) {
                            echo "RETURN";
                            $postContent = $writer->flush(true);
                            $code  = "\n" . $this->mJsHead;
                            $code .= "\n\t" .  'PHP.print("' . str_replace("\n", '\n', addslashes($preContent)) . '");';
                            $code .= "\n\t" . $childContent;
                            $code .= "\n\t" . 'PHP.print("' . str_replace("\n", '\n', addslashes($postContent)) . '");';
                            $code .= "\n" . $this->mJsFoot;
                            highlight_string($code);
                            if ($this->mParent !== NULL)
                                $this->mParent->setChildContent($code);

                            return $code;
                        }
                        break;

                    default:
                        echo "NO PARSER";
                        break;
                }

            }

        }

    }
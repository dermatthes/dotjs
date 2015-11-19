<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.11.15
 * Time: 18:24
 */

    namespace dotjs\v10\template;

    class TemplateParser {


        /**
         * @var TargetLanguage
         */
        private $mTargetLanguage;


        public function __construct (TargetLanguage $targetLanguage) {
            $this->mTargetLanguage = $targetLanguage;
        }



        private function generatePrintedCode ($codePrePost, $generatedCode, $postContent, $childContent, $readerDepth) {

            $code = "";
            if (is_array($codePrePost))
                $code  .= "\n" . $codePrePost[0];

            $plusTab = "";
            if (is_array($codePrePost))
                $plusTab = "\t";

            if (is_string($codePrePost)) {
                if ($childContent !== "") {
                    $code .= $childContent;
                }
                $code .= $codePrePost;

            } else {
                if ($childContent === "")
                    $generatedCode .= $postContent;
                $code .= "\n\t{$plusTab}" . $this->mTargetLanguage->transformInlineToCode($generatedCode) ;
                if ($childContent !== "") {
                    $code .= $childContent;
                    $code .= "\n\t{$plusTab}" . $this->mTargetLanguage->transformInlineToCode($postContent);
                }
            }
            if (is_array($codePrePost))
                $code .= "\n" . $codePrePost[1];

            $indent = str_repeat("\t", $readerDepth);

            $code = str_replace("\n", "\n{$indent}", $code);
            return $code;
        }


        /**
         * Parse
         *
         * @param $xmlInput
         * @return string
         * @throws \Exception
         */
        public function parse ($xmlInputString) {
            $xmlReader = new \XMLReader();
            $xmlReader->xml($xmlInputString);
            $xmlReader->read(); // Read till first Element before pushing to parseRecursive.
            if ($xmlReader->nodeType !== \XMLReader::ELEMENT && $xmlReader->nodeType !== \XMLReader::DOC_TYPE)
                throw new \InvalidArgumentException("First tag must be ELEMENT-type: type: {$xmlReader->nodeType} found");
            return $this->parseRecursive($xmlReader);
        }


        private function parseRecursive (\XMLReader $reader, $readerDepth = 0) {
            $generatedCode = "";
            $codePrePost = NULL;
            $childContent = "";

            $debugMyOpenedName = NULL;




            $isEmptyElement = $reader->isEmptyElement;
            $debugMyOpenedName = $reader->name;
            $generatedCode .= "<{$reader->name}";
            $attributeBag = new XmlAttributeBag();

            while($reader->moveToNextAttribute()) {
                $attributeBag->injectAttribute($reader->name, $reader->value);
            }
            $codePrePost = $this->mTargetLanguage->getPrePostCodeForElement($debugMyOpenedName, $attributeBag, $reader);

            //echo "CodePrePost: "; print_r ($codePrePost);

            foreach ($attributeBag->getAttributesArr() as $cur) {
                $generatedCode .= " {$cur[0]}=\"{$cur[1]}\"";
            }

            if ($isEmptyElement) {
                $generatedCode .= "/>";
                return $this->generatePrintedCode($codePrePost, $generatedCode, "", "", $readerDepth);
            } else {
                $generatedCode .= ">";
            }

            while ($reader->read()) {
                //echo "\n<br>R:{$readerDepth} type={$reader->nodeType} name={$reader->name} value={$reader->value} prefix={$reader->prefix} namesapceURI={$reader->namespaceURI} localName={$reader->localName}";



                switch ($reader->nodeType) {
                    case \XMLReader::ELEMENT:
                        $childContent .= $this->parseRecursive($reader, $readerDepth+1);
                        break;

                    case \XMLReader::TEXT:
                        $generatedCode .= $reader->value;
                        break;

                    case \XMLReader::WHITESPACE:
                        $generatedCode .= $reader->value;
                        break;

                    case \XMLReader::SIGNIFICANT_WHITESPACE:
                        $generatedCode .= $reader->value;
                        break;

                    case \XMLReader::CDATA:
                        $generatedCode .= "<![CDATA[{$reader->value}]]>";
                        break;

                    case \XMLReader::ENTITY_REF:

                        //  writer.WriteEntityRef(reader.Name);

                        break;

                    case \XMLReader::XML_DECLARATION:
                        break;
                    /*
                                case XmlNodeType.ProcessingInstruction:

                                      //writer.WriteProcessingInstruction( reader.Name, reader.Value );

                                      break;*/



                    case \XMLReader::COMMENT:
                        $generatedCode .= "<!-- {$reader->value} -->";
                        break;

                    case \XMLReader::END_ELEMENT:
                        if ($debugMyOpenedName === NULL)
                            throw new \Exception("Trying to close empty element");
                        if ($debugMyOpenedName !== $reader->name)
                            throw new \Exception("Element {$debugMyOpenedName} is beeing closed by {$reader->name}");
                        $postContent = "</{$reader->name}>";
                        return $this->generatePrintedCode($codePrePost, $generatedCode, $postContent, $childContent, $readerDepth);
                        break;

                    default:
                        echo "NO PARSER";
                        break;
                }

            }

        }

    }
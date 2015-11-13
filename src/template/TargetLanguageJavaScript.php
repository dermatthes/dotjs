<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 13.11.15
 * Time: 13:30
 */


    namespace dotjs\template;



    use dotjs\parser\XmlRewriter;

    class TargetLanguageJavaScript implements TargetLanguage {

        private $inlineStartTag = "[[";
        private $inlineEndTag = "]]";


        private $mIndexCounter = 0;


        public function __construct () {

        }


        public function getPrePostCodeForElement ($tag, XmlAttributeBag $attributes, \XMLReader $reader) {
            if ($tag == "script" && $attributes->getAttr("remote") !== null) {
                return $reader->readInnerXml();
            }

            if (($val = $attributes->getAttr("dot-if")) !== NULL)
                return ["if({$val}){", "};"];

            if (($val = $attributes->getAttr("dot-for")) !== NULL)
                return ["for({$val}){", "};"];

            if (($val = $attributes->getAttr("dot-repeat")) !== NULL) {
                if ( ! preg_match ('/([a-z0-9\.\_]+)\s+in\s+([a-z0-9\.\_]+)(|\s+index\s+by\s+([a-z0-9\_]+))/ims', $val, $matches)) {
                    return "Warning: Cannot parse dot-repeat=\"$val\": Please use 'localName in arrayName [index by indexName]' - syntax";
                }

                $localName = $matches[1];
                $arrayName = $matches[2];
                $indexName = isset ($matches[4]) ? $matches[4] : "index" . $this->mIndexCounter++;

                return ["for(var {$indexName}; {$indexName} < {$arrayName}.length; {$indexName}++){\n\tvar {$localName} = {$arrayName}[{$indexName}];", "};"];
            }
            return NULL;
        }


        private function _onInlineContent ($code) {



            return '");PHP.print(' . $code . ');PHP.print("';
        }

        public function transformInlineToCode ($content) {
            $startTag = preg_quote($this->inlineStartTag);
            $endTag = preg_quote($this->inlineEndTag);

            if (strpos($content, $this->inlineStartTag) !== false) {
                // Nur ausführen, wenn ein Start-Tag gesichtet wurde.

                // Escape everything left of inline Elements
                $content = preg_replace_callback("/^(.*?){$startTag}/ims", function ($matches) {
                    return addslashes($matches[1]) . $this->inlineStartTag;
                }, $content);
                // Escape everything right of inline Elements
                $content = preg_replace_callback("/{$endTag}(.*?)$/ims", function ($matches) {
                    return $this->inlineEndTag . addslashes($matches[1]);
                }, $content);

                // Escape everything between two or more Inline-Elements
                $content = preg_replace_callback("/{$endTag}(.*?){$startTag}/ims", function ($matches) {
                    return $this->inlineEndTag . addslashes($matches[1]) . $this->inlineStartTag;
                }, $content);

                // Do the actual processing of Content:
                $content = preg_replace_callback("/{$startTag}(.*?){$endTag}/ims", function ($matches) {
                    return $this->_onInlineContent($matches[1]);
                }, $content);
            }

            $code = "\n\tPHP.print(";
            $blocks = [];
            foreach (explode("\n", $content) as $curLine) {
                $blocks[] = '"' . $curLine . '"';
            }
            $code .= implode("\n\t\t+ ", $blocks);
            $code .= ');';
            return $code;
        }





    }

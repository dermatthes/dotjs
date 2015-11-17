<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 17.11.15
 * Time: 17:30
 */


    namespace dotjs\template;


    class TemplateControllerExtractor {



        public function parse ($xmlInputString) {
            $xmlReader = new \XMLReader();
            $xmlReader->xml($xmlInputString);
            $outCtrl = "";
            while ($xmlReader->read()) {
                if ($xmlReader->nodeType !== \XMLReader::ELEMENT) {
                    continue;
                }

                if ($xmlReader->name !== "script")
                    continue;

                if ($xmlReader->getAttribute("remote") === NULL)
                    continue;

                $outCtrl .= "\n" . $xmlReader->readInnerXml();

            }
            return $outCtrl;
        }


    }
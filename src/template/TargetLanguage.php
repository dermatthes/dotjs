<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 13.11.15
 * Time: 14:12
 */


    namespace dotjs\template;


    interface TargetLanguage {

        /**
         * Return:
         * -------
         * NULL:                    Unterelemente werden als Text ausgegeben
         * [codePre, codePost]      Dieser Programmiercode wird vor und hinter die Elemente gesetzt
         * string:                  Code durch den dieses Element ersetzt wird.
         *
         *
         * @param $tag
         * @param XmlAttributeBag $attributes
         * @return mixed
         */
        public function getPrePostCodeForElement ($tag, XmlAttributeBag $attributes, \XMLReader $reader);

        public function transformInlineToCode ($content);

    }
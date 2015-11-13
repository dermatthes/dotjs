<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.11.15
 * Time: 14:30
 */


    namespace dotjs\template;

    class XmlAttributeBag {

        private $mAttributes = [];


        public function injectAttribute ($name, $value) {
            $attrib = [$name, $value];
            $this->mAttributes[] = $attrib;
        }



        public function removeAttribute ($name) {
            $new = [];
            foreach ($this->mAttributes as $cur) {
                if ($cur[0] == $name)
                    continue;
                $new[] = $cur;
            }
            $this->mAttributes = $new;
        }

        public function getAttr ($name) {
            foreach ($this->mAttributes as $curAtt) {
                if ($curAtt[0] == $name)
                    return $curAtt[1];
            }
            return null;
        }

        public function getAttributesArr () {
            return $this->mAttributes;
        }

    }
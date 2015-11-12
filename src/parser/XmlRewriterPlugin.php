<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.11.15
 * Time: 15:18
 */

    namespace dotjs\parser;


    interface XmlRewriterPlugin {

        public function register (XmlRewriter $rewriter);

    }
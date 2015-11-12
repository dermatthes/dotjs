<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.11.15
 * Time: 19:19
 */



use dotjs\template\TemplateParser;

require(__DIR__ . "/../../autoload.php");

$tp = new TemplateParser();
$reader = new XMLReader();
$reader->xml(file_get_contents("template.xml"));

highlight_string($tp->parse($reader));
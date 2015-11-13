<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.11.15
 * Time: 19:19
 */


use dotjs\template\TargetLanguageJavaScript;
use dotjs\template\TemplateParser;

require(__DIR__ . "/../../autoload.php");

$tp = new TemplateParser(new TargetLanguageJavaScript());


highlight_string($tp->parse(file_get_contents("template.xml")));
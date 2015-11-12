<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.11.15
 * Time: 09:45
 */

use dotjs\parser\DotJsTemplate;
use dotjs\parser\XmlRewriter;
use dotjs\plugins\DotJsRepeatPlugin;
use dotjs\plugins\DotJsTemplatePlugin;

require __DIR__ . "/../autoload.php";



$rewriter = new XmlRewriter();
$rewriter->getXmlWriter()->openMemory();
$rewriter->getXmlReader()->XML(file_get_contents("test.xml"));
$rewriter->addPlugin(new DotJsRepeatPlugin($v8 = new V8Js()));
$v8->print = function ($what) use ($rewriter) {
      $rewriter->getXmlWriter()->writeRaw($what);
};
$rewriter->addPlugin(new DotJsTemplatePlugin($v8, $rewriter->getXmlWriter()));


$rewriter->rewrite();


echo "<br><br>";
highlight_string($rewriter->getXmlWriter()->outputMemory());

?>

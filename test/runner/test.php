<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 16.11.15
 * Time: 16:39
 */

require(__DIR__ . "/../../autoload.php");

use dotjs\server\core\FileSystemFileLoader;
use dotjs\server\v10\BridgeV10;

$bridge = new BridgeV10(new FileSystemFileLoader(__DIR__ . "/demo"));

$bridge->runTemplate("site1.html");


echo "<br><br>Output:<br>";
highlight_string($bridge->getOutput());
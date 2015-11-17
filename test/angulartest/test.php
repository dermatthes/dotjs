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


if (isset ($_GET["call"])) {
    $bridge->runController("site1.html", $_GET["call"]);
    exit;
}

$bridge->runTemplate("site1.html");


echo $bridge->getOutput();
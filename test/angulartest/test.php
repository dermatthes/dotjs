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




if (isset ($_SERVER["HTTP_X_DOTJS_ISAJAXREQUEST"])) {
    $params = explode("/", substr($_SERVER["PATH_INFO"], 1));
    $bridge->runAction("site1.html", $params[0]);
    exit;
}

$bridge->runTemplate("site1.html");


echo $bridge->getOutput();
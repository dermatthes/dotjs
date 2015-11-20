<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 16.11.15
 * Time: 16:39
 */

require(__DIR__ . "/../../autoload.php");

use dotjs\v10\modules\FIREPHP\Ext_FIREPHP;
use dotjs\v10\server\core\FileSystemFileLoader;
use dotjs\v10\server\DotJsBridge;

$bridge = new DotJsBridge(new FileSystemFileLoader(__DIR__ . "/demo"));
$bridge->addLowLevelExtension(new Ext_FIREPHP());




if (isset ($_SERVER["HTTP_X_DOTJS_ISAJAXREQUEST"])) {
    $params = explode("/", substr($_SERVER["PATH_INFO"], 1));
    $bridge->runAction("site1.html", $params[0]);
    exit;
}

$bridge->runTemplate("site1.html");


echo $bridge->getOutput();
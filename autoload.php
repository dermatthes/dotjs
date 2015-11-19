<?php


namespace dotjs\v10;


spl_autoload_register(function ($class) {

    if (substr($class, 0, strlen(__NAMESPACE__)) != __NAMESPACE__)
        return;
    $path = __DIR__ . "/src/" . str_replace("\\", "/", substr($class, strlen(__NAMESPACE__))) . ".php";
    require_once($path);
});
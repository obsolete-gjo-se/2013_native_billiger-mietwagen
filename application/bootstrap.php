<?php

error_reporting(E_ALL | E_STRICT);

defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(__DIR__));

defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

set_include_path(implode(PATH_SEPARATOR, array(
        realpath(APPLICATION_PATH),
        get_include_path(),
)));

function __autoload($class){

    $filename = __DIR__ . "/" . strtolower($class) . ".php";
    require_once($filename);

}
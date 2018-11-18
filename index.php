<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
//load configuration and helper functions

require_once(ROOT . DS . 'config' . DS . 'config.php');
require_once(ROOT . DS . 'app' . DS . 'lib' . DS . 'helpers' . DS . 'functions.php');

//autoload classes

function coreDirectoryAutoload(string $className)
{
    $fullDirectoryName = ROOT . DS . 'core' . DS . $className . '.php';
    if (file_exists($fullDirectoryName)) {
        require_once("$fullDirectoryName");
    }
}

function controllerDirectoryAutoload(string $className)
{

    $fullDirectoryName = ROOT . DS . 'app' . DS . 'controllers' . DS . $className . '.php';
    if (file_exists($fullDirectoryName)) {
        require_once("$fullDirectoryName");
    }
}

function modelDirectoryAutoload(string $className)
{

    $fullDirectoryName = ROOT . DS . 'app' . DS . 'models' . DS . $className . '.php';
    if (file_exists($fullDirectoryName)) {
        require_once("$fullDirectoryName");
    }
}


spl_autoload_register('coreDirectoryAutoload');
spl_autoload_register('controllerDirectoryAutoload');
spl_autoload_register('modelDirectoryAutoload');
session_start();

$url = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'], '/')) : [];
//ROUTE THE REQUEST
Router::route($url);
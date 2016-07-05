<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 05.07.16
 * Time: 15:09
 *
 * autoload register function
 */
spl_autoload_register(function($class){
    $classPath = explode('\\',$class);
    $result = array();
    foreach ($classPath as $_classPath) {
        $result[] = ucfirst($_classPath);
    }
    $pathToClass = implode(DIRECTORY_SEPARATOR,$result);
    $pathToClass = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.$pathToClass.'.php';
    $pathToClass = realpath($pathToClass);
    require_once $pathToClass;
});
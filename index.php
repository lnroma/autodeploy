<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 05.07.16
 * Time: 14:58
 */
include_once 'Classes/Autoload.php';
$main = new classes\Main();

if($main->getArgument('server')) {
    $main->runDeployServer();
} elseif($main->getArgument('deploy')) {
    $main->sendDeployCommand();
} 

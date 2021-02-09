<?php

use Component\App;

//Режим разработчика
define('DEV', true);

if(DEV){
    ini_set('display_errors',1);
    error_reporting(E_ALL);
}

define('ROOT', dirname(__FILE__));

require_once(ROOT.'/vendor/autoload.php');

session_start();

App::run();
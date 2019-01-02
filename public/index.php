<?php

ob_start();

/**
 * Composer Packages Autoload
 */
require('../vendor/autoload.php');

/**
 * Functions
 */
require('../functions.php');

/**
 * Application Configuration
 */
$config = require('../config.php');

/**
 * Database
 */
$db = require('../database.php');

/**
 * Iyzipay Integration
 */
$iyziOptions = new \Iyzipay\Options();
$iyziOptions->setApiKey($config['iyzico']['apiKey']);
$iyziOptions->setSecretKey($config['iyzico']['apiSecret']);
$iyziOptions->setBaseUrl($config['iyzico']['baseUrl']);

/**
 * Routing
 */
$controller = isset($_GET['c']) ? $_GET['c'] : "home";
$action = isset($_GET['a']) ? $_GET['a'] : "index";

include "header.php";

if(file_exists($controllerFile = __DIR__."/../controllers/".$controller."/".$action.".php"))
    include $controllerFile;
else
    include "404.php";

include "footer.php";

ob_end_flush();
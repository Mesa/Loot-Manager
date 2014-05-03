<?php

namespace Mesa\Commander\Core;

use Commander\Core\Environment;
use Commander\Core\System;
use Doctrine\Common\Annotations\Annotation;

require "errorHandler.php";

try {

    if (!defined("ISPHAR")) {
        define("ISPHAR", false);
    }

    define("DS", DIRECTORY_SEPARATOR);
    if (!defined("ROOT")) {
        if (ISPHAR) {
            define("ROOT", realpath("./") . DS);
        } else {
            define("ROOT", realpath(__DIR__ . "/../../../") . DS);
        }
    }

    if (ISPHAR == false && getcwd() !== ROOT) {
        chdir(ROOT);
    }

    require __DIR__ . "/../Exception/FileNotFoundException.php";
    require __DIR__ . "/../../Mesa/Lib/SplClassLoader.php";

    require __DIR__ . "/Environment.php";
    require __DIR__ . "/System.php";
    require_once __DIR__ . "/../../../vendor/autoload.php";

    if (!defined("ENVIRONMENT")) {
        Environment::set(Environment::PROD);
    } else {
        Environment::set(ENVIRONMENT);
    }

    define("DEFAULT_CONFIG", __DIR__ . "/../Config/config.php");

    $app = new System();

    $app->addBundle('Commander', ROOT . "src");
    $app->addBundle('App', realpath(ROOT));

} catch (\Exception $e) {
    echo $e;
    $logger = $app->getLogger();
    $logger->addCritical($e);
}

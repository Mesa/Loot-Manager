<?php
$start = microtime();

if (in_array(
    $_SERVER["REMOTE_ADDR"],
    ["127.0.0.1", "192.168.0.21"]
)
) {
    define("ENVIRONMENT", "DEV");
    error_reporting(-1);
} else {
    define("ENVIRONMENT", "PROD");
    error_reporting(0);
}

define("CONFIG_FILE", "App/Config/config_dev.php");
define("SERVICE_FILE", __DIR__ . "/../App/Config/services.php");

require "../src/Commander/Core/bootstrap.php";

try {
    echo $app->run();
} catch (Exception $e) {
    echo $e;
}

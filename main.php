#!/usr/bin/env php
<?php

define("ENVIRONMENT", "DEV");
error_reporting(-1);

if ("phar" == substr(__DIR__, 0, 4)) {
    define("ISPHAR", true);
} else {
    define("ISPHAR", false);
}

#define("CONFIG_FILE", "App/Config/config.php");

require 'src/Commander/Core/bootstrap.php';

try {
    echo $app->run();
} catch (\Exception $e) {
    echo $e;
}


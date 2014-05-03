#!/usr/bin/php
<?php

use Symfony\Component\Console\Helper\HelperSet;

define("ENVIRONMENT", "DEV");
define("CONFIG_FILE", "App/Config/config_dev.php");
define("SERVICE_FILE", __DIR__ . "/App/Config/services.php");
error_reporting(-1);

require 'vendor/autoload.php';
require 'src/Commander/Core/bootstrap.php';

$app->prepare();

$em = $app->getEntityManager();

$helperSet = new HelperSet(
    array(
        'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
        'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
    )
);

\Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet);


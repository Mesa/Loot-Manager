<?php

/**
 * Mesas Loot Manager
 * Copyright (C) 2011  Mesa <Daniel Langemann>
 *
 * Php version 5.3
 *
 * @category Installer
 * @package  Mesas_Loot_Manager
 * @author   Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Install;

error_reporting(E_ALL);
define("DS", DIRECTORY_SEPARATOR);
define("PRODUCTION_USE", false);

mb_internal_encoding("UTF-8");

if (isset($_GET["action"])) {
    $action = $_GET["action"];
} else {
    $action = $_POST["action"];
}

switch ($action) {

case "file_permission":
    checkFilePermission($_POST["path"]);
    break;

case "folder_permission":
    checkFolderPermission($_POST["path"]);
    break;

case "run":
    runXml();
    break;

case "php_version":
    checkPhpVersion();
    break;

case "check_db_connection":
    checkDbConnection();
    break;

case "pdo_version":
    checkPdoVersion();
    break;

case "rewrite_test":
    echo "true";
    break;
default:
    echo "action not found";
    break;
}

/**
 * check MySQL login information
 *
 * @return [String]
 */
function checkDbConnection ()
{

    try {
        $db_con = new \PDO(
            'mysql:host=' . $_POST["host"] .
            ';dbname=' . $_POST["shema"] .
            ';port=' . (int) $_POST["port"],
            $_POST["username"],
            $_POST["password"],
            array(
                \PDO::ATTR_PERSISTENT => true,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            )
        );
    } catch (\PDOException $exc) {
        echo "<h1>Ups....</h1>";
        echo $exc->getMessage();
    }
}

/**
 * check folder permission
 *
 * @param string $path folder path
 *
 * @return [String]
 */
function checkFolderPermission ( $path )
{

    $path = dirname(dirname(__DIR__)) . DS . $path;

    $random_name = dirname(dirname(__DIR__)) . DS . md5("JackassPhP" . time());

    $test = @mkdir($random_name);

    if ($test === true) {
        echo "true";
        @rmdir($random_name);
    } else {
        echo "false";
    }
}

/**
 * check File permission
 *
 * @param string $path Path to folder
 *
 * @return [String]
 */
function checkFilePermission ( $path )
{
    if ($path == ".") {
        $path = __DIR__ . \DIRECTORY_SEPARATOR;
    }

    if (file_exists($path)) {
        $file_handle = @fopen($path . "text.txt", "a+");
        @fwrite($file_handle, "test");
        @fclose($file_handle);
        if (file_exists($path . "text.txt")) {
            unlink($path . "text.txt");
            echo "true";
        } else {
            echo "false";
        }
    } else {
        echo $path . " doesnt exist";
    }
}

function checkPdoVersion ()
{
    $version = phpversion("PDO");

    if ($version !== false) {
        echo $version;
    } else {
        echo "false";
    }
}

function checkPhpVersion ()
{
    if (\PHP_VERSION_ID > 50300) {
        echo \PHP_VERSION_ID;
    } else {
        echo false;
    }
}

function runXml ()
{

    $root = dirname(__DIR__) . \DIRECTORY_SEPARATOR;

    $web_root = dirname(dirname(__DIR__));

    include $root . 'Helper/Installer.php';
    include $root . 'Core/MySQLDB.php';
    include $root . 'Core/Registry.php';

    try {
        $db_con = new \PDO(
            'mysql:host=' . $_GET["host"] .
            ';dbname=' . $_GET["shema"] .
            ';port=' . (int) $_GET["port"],
            $_GET["username"],
            $_GET["password"],
            array(
                \PDO::ATTR_PERSISTENT => true,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            )
        );
    } catch (\PDOException $exc) {
        echo $exc->getMessage();
    }
    /**
     * we will use UTF-8 chars, so we tell it to the database.
     */
    $db_con->query("set character set utf8");
    $install_file = "install.xml";
    if (file_exists($install_file)) {

        $account_username = base64_encode($_GET["acc_username"]);
        $account_password = hash('sha256', $_GET["acc_password"]);
        $file_content = file_get_contents($install_file);
        $file_content =
            str_replace("{ACCOUNT_NAME}", $account_username, $file_content);
        $file_content =
            str_replace("{ACCOUNT_PASSWORD}", $account_password, $file_content);
        $file_content =
            str_replace("{MYSQL_USERNAME}", $_GET["username"], $file_content);
        $file_content =
            str_replace("{MYSQL_PASSWORD}", $_GET["password"], $file_content);
        $file_content =
            str_replace("{MYSQL_HOST}", $_GET["host"], $file_content);
        $file_content =
            str_replace("{MYSQL_SHEMA}", $_GET["shema"], $file_content);
        $file_content = str_replace("{MYSQL_PORT}", $_GET["port"], $file_content);
        $file_handle = fopen("temp_install.xml", "w");
        fwrite($file_handle, $file_content, strlen($file_content));
        fclose($file_handle);

        $installer = new \JackAssPHP\Helper\Installer($root . "Install/temp_install.xml", $db_con);
    }

    $application_data = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "Application/Config/update.xml";

    if (file_exists($application_data)) {
        new \JackAssPHP\Helper\Installer($application_data, $db_con);
    }
}
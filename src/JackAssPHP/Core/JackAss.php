<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Core;

require ROOT . 'JackAssPHP/Exceptions/DefaultException.php';
require ROOT . 'JackAssPHP/Exceptions/RightException.php';
require ROOT . 'JackAssPHP/Exceptions/ViewException.php';
require ROOT . 'JackAssPHP/Exceptions/HtmlException.php';
require ROOT . 'JackAssPHP/Exceptions/FileException.php';

set_exception_handler('DefaultException');
if (PRODUCTION_USE === true) {
    error_reporting(E_ALL);
    ini_set('log_errors', '1');
    ini_set('display_errors', '0');
} else {
    error_reporting(E_ALL);
//    include 'ErrorHandler.php';
//    set_error_handler("JackAssErrorHandler");
    ini_set('log_errors', '0');
    ini_set('display_errors', '1');
}

require ROOT . 'JackAssPHP/Core/security.php';
require ROOT . 'JackAssPHP/Core/autoloader.php';

$autoload = new SplClassLoader(null, ROOT);
$autoload->register();
//$autoload = new SplClassLoader("Application", ROOT);
//$autoload->register();
//$autoload = new SplClassLoader("JackAssPHP", ROOT);
//$autoload->register();

require ROOT . 'JackAssPHP/Core/Factory.php';
require ROOT . 'Application/Config/System.php';

$blacklist = \Factory::getBlackList();

/**
 * get the web root for your Application
 */

$registry->set("WEB_ROOT", "http://" . $_SERVER["SERVER_NAME"] . str_replace("index.php", "", $_SERVER["PHP_SELF"]));

if (substr($_SERVER['DOCUMENT_ROOT'], -1) == "/") {
    $root_folder = strtolower(
        str_replace(substr($_SERVER['DOCUMENT_ROOT'], 0, -1), "", dirname($_SERVER['SCRIPT_FILENAME'])) . "/"
    );
} else {
    $root_folder = strtolower(str_replace($_SERVER['DOCUMENT_ROOT'], "", dirname($_SERVER['SCRIPT_FILENAME'])) . "/");
}

/**
 * Define the Root folder, where this Application is located on the Server.
 */
if (strlen($root_folder) > 1) {
    /**
     * This Application is NOT located in the DOCUMENT_ROOT
     */

    $registry->set("REQUEST_PATH", str_replace($root_folder, "", strtolower($_SERVER['REQUEST_URI'])));
} else {
    /**
     * This Application is locate in the DOCUMENT_ROOT. Single Page Server or Subdomain.
     */
    if (strlen($_SERVER["REQUEST_URI"]) <= 1) {
        $registry->set("REQUEST_PATH", "");
    } else {
        $registry->set("REQUEST_PATH", substr($_SERVER['REQUEST_URI'], 1, strlen($_SERVER['REQUEST_URI'])));
    }
}

$registry->set("REQUEST_ARGS", explode("/", $registry->get("REQUEST_PATH")));
$registry->set("REQUEST_METHOD", htmlentities($_SERVER['REQUEST_METHOD']));


include_once $registry->get("CONFIG_PATH") . 'Routes.php';
try {
    $Router = new Router($registry, $route);
} catch (\ViewException $exc) {
    echo $exc->errorMessage();
} catch (\RouteException $exc) {
    echo $exc->errorMessage();
} catch (\HtmlException $exc) {
    echo $exc->errorMessage();
} catch (\PDOException $exc) {
    if (\PRODUCTION_USE == false) {
        $code = $exc->getCode();
        switch ($code) {
            case "42S02":
                /**
                 * Table not found
                 */
                new \JackAssPHP\Error\ExceptionErrorMessage($exc, "TABLE");
                break;

            default:
                echo $exc->getMessage();
        }
    } else {
        echo "Database error";
    }
} catch (\Exception $exc) {

    if (!\PRODUCTION_USE) {
        echo $exc->getMessage();
    } else {
        /**
         * @todo: write Exception to log
         */
    }
}

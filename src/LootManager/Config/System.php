<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

$registry = \Factory::getRegistry();
/**
 * default Method to trigger
 */
$registry->set("DEFAULT_METHOD", "index");
/**
 * Default Controller to trigger when no Route matches. Could be used for
 * Errorhandling.
 */
$registry->set("DEFAULT_CONTROLLER" , "FrontPage");
/**
 * the leading backslash is important!!!!!
 */
$registry->set("DEFAULT_NAMESPACE" , "\\Application\\Controller\\");

/**
 * Some Project wide Informations
 */
$registry->set("VERSION_NR" , "0.0.1");

$registry->set("SYSTEM_NAME", "Loot-Manager");

$registry->set("USER_TABLE" , "user");

/**
 * Default language, when users language could not be detected or is not
 * supported
 */
$registry->set("DEFAULT_LANG" , "en");

/**
 * Login
 */
$registry->set("MIN_PASSWORD_LENGTH" , 6);

$registry->set("LOGIN_PATH" , "login/1");
/**
 * Redirect User to this url, when not logged in and Login is required
 */
$registry->set("LOGIN_REDIRECT_PATH" , "login/");

$registry->set("META_AUTHOR_NAME", "Mesa (Daniel Langemann)");
$registry->set("META_ROBOTS", "all");
$registry->set("REGISTER_PATH", "register/");
$registry->set("VALIDATE_USERNAME_REGEX", "/[a-z0-9_äÄöÖüÜß_\-.?]{2,}/i");
$registry->set("VALIDATE_EMAIL_REGEX", "/^[a-z0-9][a-z0-9_äÄöÖüÜß\-\.]+@[a-z0-9_äÄöÖüÜß\-\.]+\.[a-z]{2,4}$/i");
$registry->set("VALIDATE_SPECIAL_CHAR_REGEX", "/[\!\/\\?§$%&\(\)=\"\-_@]/");
/**
 * Set the name of the used captcha class
 */
$registry->set("CAPTCHA_CLASS", "ReCaptcha");
$registry->set("CAPTCHA_REDIRECT_PATH", "captcha/show/");
$registry->set("CAPTCHA_VERIFY_PATH", "captcha/verify/");

/**
 * Blacklist config
 */
$registry->set("MAX_FALSE_LOGIN_TRIES" , 5);
$registry->set("LOCKING_TIME", 60 * 60);

$registry->set("SYSTEM_STYLE" , "default");

$registry->set("ERROR_CONTROLLER" , "Error/notFound");

$registry->set("APPLICATION_PATH" , ROOT . "Application" . DS);
$registry->set("JAVASCRIPT_PATH" , ROOT . "Application" . DS . "JS" . DS);
$registry->set("CONFIG_PATH" , ROOT . "Application" . DS . "Config" . DS);
$registry->set("VIEW_PATH" , ROOT . "Application" . DS . "Views" . DS);
$registry->set("THEME_PATH" , ROOT . "Application" . DS . "Themes" . DS);
$registry->set("SYSTEM_PATH" , ROOT . "JackAssPHP" . DS);
$registry->set("CORE_PATH" , ROOT . "JackAssPHP" . DS . "Core" . DS);
$registry->set("HELPER_PATH" , ROOT . "JackAssPHP" . DS . "Helper" . DS);
$registry->set("LIBRARY_PATH" , ROOT . "JackAssPHP" . DS . "LIBRARY" . DS);
$registry->set("ERROR_LOG_PATH", ROOT . "JackAssPHP" . DS . "Logs" . DS . "Error" . DS);
$registry->set("INFO_LOG_PATH", ROOT . "JackAssPHP" . DS . "Logs" . DS . "Info" . DS);
/**
 * Load Project config from DB
 */
$config_DAO = new Application\Model\Config();
$data = $config_DAO->getAllData();

foreach ( $data as $item ) {
    $registry->set($item["Name"], $item["Value"]);
}

unset($config_DAO);
unset($data);
<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

/**
 * Factory Klasse um globalen Zugriff auf wichtige Klassen zu haben
 * und damit für diese Klassen nicht das Singleton genutzt werden muss.
 */
class Factory
{

    /**
     * Enthält alle erstellten Klassen objekte
     *
     * @var [Array/Object] Alle Klassen
     */
    private static $_objects = array();

    /**
     * Gibt eine Datenbankverbindung zurück, werden alle Parameter ausgelassen,
     * dann wird eine Verbindung mit den "Default" Daten erstellt. sollte noch
     * keine Verbindung bestehen wird diese erstellt, ansonsten wird nur das
     * bestehende Objekt zurückgegeben.
     *
     * @param type $host     127.0.0.1 Ip or DNS
     * @param type $username DB Benutzername
     * @param type $password DB Passwort
     * @param type $shema    DB Name
     * @param type $port     DB Port
     *
     * @return [Object] PDO verbindung
     */
    public static function getDB (
        $host = null,
        $username = null,
        $password = null,
        $shema = null,
        $port = null
    ) {
        if (!isset(self::$_objects["DB"])) {
            self::$_objects["DB"] = array();
        }

        if (!array_key_exists($host, self::$_objects["DB"])) {

            /* If no data is suplied, use MySQL default data from mysql.ini */
            if ($host == null) {
                $data = parse_ini_file(ROOT . DS . "mysql.ini");
                $host = $data["host"];
                $username = $data["username"];
                $password = $data["password"];
                $shema = $data["shema"];
                $port = $data["port"];
            }

            $connection = JackAssPHP\Core\MySQLDB::getInstance(
                $host,
                $username,
                $password,
                $shema,
                $port
            );
        }

        self::$_objects["DB"][$host] = $connection;

        return self::$_objects["DB"][$host];
    }

    /**
     * Das Registry Objekt anfordern.
     *
     * @return [Object] Registry class
     */
    public static function getRegistry ()
    {
        if (!array_key_exists("Registry", self::$_objects)) {
            self::$_objects["Registry"] = new JackAssPHP\Core\Registry();
        }

        return self::$_objects["Registry"];
    }

    /**
     * Das Template Objekt "View" anfordern.
     *
     * @return [Object] View class
     */
    public static function getView ()
    {

        return new JackAssPHP\Core\View(
                        self::getRegistry(),
                        self::getRights(),
                        self::getTranslate(),
                        self::getUser()
                    );
    }

    /**
     * Returns Blacklist Class
     *
     * @return [Object] Blacklist Object
     */
    public static function getBlackList ()
    {
        if (!array_key_exists("BlackList", self::$_objects)) {
            self::$_objects["BlackList"] = new JackAssPHP\Core\BlackList(
                self::getRegistry(),
                new \Application\Model\BlackList(self::getDB())
            );
        }

        return self::$_objects["BlackList"];
    }

    /**
     * Returns User Class
     *
     * @return [Object] User Class
     */
    public static function getUser ()
    {
        if (!array_key_exists("User", self::$_objects)) {

            self::$_objects["User"] = new JackAssPHP\Core\User(
                self::getRegistry(), new Application\Model\User()
            );
        }

        return self::$_objects["User"];
    }

    /**
     * Return Translate Class
     *
     * @return [Object] Translate Class
     */
    public static function getTranslate ()
    {
        if (!array_key_exists("Translate", self::$_objects)) {

            self::$_objects["Translate"] = new JackAssPHP\Helper\Translate(
                self::getDB(),
                self::getRegistry()
            );
        }

        return self::$_objects["Translate"];
    }

    /**
     * Return Class which handles User Rights
     *
     * @return [Object] Rights Class
     */
    public static function getRights ()
    {
        if (!array_key_exists("Rights", self::$_objects)) {

            self::$_objects["Rights"] = new JackAssPHP\Helper\Rights(
                self::getDB(),
                self::getRegistry()
            );
        }

        return self::$_objects["Rights"];
    }

    public static function getEmail ()
    {
        if (!array_key_exists("Email", self::$_objects)) {
            $registry = self::getRegistry();
            require ROOT . 'Library/SwiftMailer.php';
            require ROOT . "Library/swiftmailer/lib/swift_required.php";
            self::$_objects["Email"] = new SwiftMailer(
                    self::getRegistry()
                    );
        }

        return self::$_objects["Email"];
    }

    public static function getFilter ()
    {
        if ( !array_key_exists("Filter", self::$_objects)) {
            self::$_objects["Filter"] = new JackAssPHP\Helper\Filter();
        }

        return self::$_objects["Filter"];
    }

    public static function getHtmlResponse ()
    {
        if ( !array_key_exists("html_response", self::$_objects)) {
            self::$_objects["html_response"] = new JackAssPHP\Core\ResponseHtml();
            self::$_objects["html_response"]->setWebRoot(self::getRegistry()->get("WEB_ROOT"));
            self::$_objects["html_response"]->setTitle(self::getRegistry()->get("GUILD_NAME"));
            self::$_objects["html_response"]->setThemePath(
                self::getRegistry()->get("THEME_PATH").self::getRegistry()->get("SYSTEM_STYLE").DS
            );
        }
        return self::$_objects["html_response"];
    }

    public static function getLogger ( )
    {
        if ( !array_key_exists("logger", self::$_objects)) {
            self::$_objects["logger"] = new \JackAssPHP\Logger\Logger();

            self::$_objects["logger"]->setInfoLogger(
                new JackAssPHP\Logger\DbLogger(
                    Factory::getDB(),
                        "InfoLog"
                )
            );
            self::$_objects["logger"]->setErrorLogger(
                new JackAssPHP\Logger\DbLogger(
                    Factory::getDB(),
                        "ErrorLog"
                )
            );
            self::$_objects["logger"]->setErrorLogger(
                new \JackAssPHP\Logger\FileLogger(
                    self::getRegistry()->get("ERROR_LOG_PATH")
                )
            );
            self::$_objects["logger"]->setInfoLogger(
                new \JackAssPHP\Logger\FileLogger(
                    self::getRegistry()->get("INFO_LOG_PATH")
                )
            );
        }

        return self::$_objects["logger"];
    }
}
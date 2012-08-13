<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Core;

/**
 * Create a PDO Database connection and return handle.
 */
class MySQLDB
{

    /**
     *
     * @var [Object] Connection handle
     */
    protected static $connection        = array();
    protected static $default       = array("mysql" => array("port" => "3306"));
    protected static $registry          = null;
    protected static $trigger_registry  = true;
    protected static $username          = null;
    protected static $password          = null;
    protected static $host              = null;
    protected static $port              = null;
    protected static $shema             = null;

    /**
     * Get Instance of an PDO DB connection. When connection not exists, it will be
     * created.
     *
     * @param [String] $host     IP Adress
     * @param [String] $username Username
     * @param [String] $password Password
     * @param [String] $shema    Shema
     * @param [Int]    $port     Port
     *
     * @return type
     */
    static public function getInstance
    (
        $host     = null,
        $username = null,
        $password = null,
        $shema    = null,
        $port     = null
    ) {
        if (!isset(self::$connection[$host])) {
            try {
                /**
                 * the quick and dirty way to suppress the warning, do i need this
                 * warning when i'am catching the Exception? I'm not sure.
                 *
                 */
                @self::$connection[$host]
                    = new \PDO(
                        'mysql:host=' . $host .
                        ';dbname=' . $shema .
                        ';port=' . (int) $port,
                        $username,
                        $password,
                        array(
                            \PDO::ATTR_PERSISTENT => true,
                            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                        )
                    );
            } catch (\PDOException $e) {
                $errorCode = $e->getCode();

                if (2002 == $errorCode) {
                    /**
                     * No connection to server
                     */
                    self::$connection[$host] = false;
                    $error = new \JackAssPHP\Core\Error();
                    $error->noDBConnection($e->getMessage());
                } elseif (1049 == $errorCode) {
                    /**
                     * Unknown SCHEMA
                     *
                     */
                    die("Database error.");
                } elseif (1044 == $errorCode or 1045 == $errorCode) {
                    /**
                     * Connection refused, because wrong login information
                     * username / password
                     *
                     * [1044] Access denied for user '<user>'@'%' to database
                     * '<database>'
                     *
                     * @todo: add errorhandling for wrong MYSQL login information
                     */
                    if (\PRODUCTION_USE === false) {
                        echo $e->getMessage();
                    } else {
                        echo "The System has enocuntered a problem.";
                    }
                    die();
                } else {
                    /**
                     * Exception thrown, but Errorcode == 00
                     *
                     * for example: could not find driver
                     */
                    if (\PRODUCTION_USE === false) {

                        die($e->getMessage());
                    }

                    self::$connection[$host] = false;
                }
            }
            /**
             * Tell the server we will accept UTF8 chars. There are more
             * languages than english, so welcome special chars.
             */
            if (false !== self::$connection[$host]) {
                /**
                 * tell the registry, the db connection is established and could
                 * be used from now
                 */
                if (self::$trigger_registry) {
                    self::$trigger_registry = false;
                }
                self::$connection[$host]->query("set character set utf8");
            }
        }

        /**
         * return the Database object or false on error. The Application must handle
         * the Error !!!!
         */
        return self::$connection[$host];
    }

}

?>

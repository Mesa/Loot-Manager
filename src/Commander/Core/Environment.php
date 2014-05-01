<?php
namespace Commander\Core;

/**
 * Class Environment
 *
 * @package Commander\Core
 */
class Environment
{
    const PROD = "PROD";
    const DEV  = "DEV";
    const TEST = "TEST";
    /**
     * @var
     */
    private static $env;

    /**
     * @param string $env
     */
    public static function set($env)
    {
        self::$env = $env;
    }

    /**
     * @return string
     */
    public static function current()
    {
        return self::$env;
    }
}

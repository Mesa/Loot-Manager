<?php
/**
 * Created by PhpStorm.
 * User: mesa
 * Date: 03.05.14
 * Time: 19:50
 */

namespace Commander\Exception;


class NoAccessException extends \Exception
{

    function __toString()
    {
        return "Access denied";
    }
}
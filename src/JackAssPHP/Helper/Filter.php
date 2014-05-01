<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Helper;

class Filter
{
    /**
     * Validate E-Mail address
     *
     * @param [String] $email
     *
     * @return boolean
     */
    static public function validateEmail ( $email )
    {
//        preg_match("/^[\w-\_\.]+@[\w\-\.]+\.[a-z]{2,3}$/", $email, $match);
        preg_match("/^[a-z0-9][a-z0-9_äÄöÖüÜß\-\.]+@[a-z0-9_äÄöÖüÜß\-\.]+\.[a-z]{2,4}$/i", $email, $match);

        if ( count($match) == 1 ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validate ip string, there are no test executed to check if host is down etc..
     *
     * @param string $ip
     *
     * @return boolean
     */
    static public function validateIp ( $ip )
    {
        preg_match("/^[1-9]\d*\.\d{1,3}\.\d{1,3}.\d{1,3}$/", $ip, $match);

        if( count($match) == 1 ) {
            return true;
        } else {
            return false;
        }
    }
}

?>

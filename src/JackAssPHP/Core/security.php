<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

array_walk_recursive($_GET, 'filter');
array_walk_recursive($_POST, 'filter_chars');
array_walk_recursive($_COOKIE, 'filter');
array_walk_recursive($_REQUEST, 'filter');
$_SERVER["REQUEST_URI"] = urldecode($_SERVER["REQUEST_URI"]);
/**
 * Filter all vars which could be manipulated from the user
 */
filter($_SERVER["REQUEST_URI"]);
filter($_SERVER["HTTP_USER_AGENT"]);
filter($_SERVER["HTTP_ACCEPT"]);
filter($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
filter($_SERVER["HTTP_ACCEPT_ENCODING"]);
filter($_SERVER["HTTP_COOKIE"]);
filter($_SERVER["HTTP_CACHE_CONTROL"]);
filter($_SERVER["HTTP_CONNECTION"]);

function filter ( &$value, $key = null )
{
    static $search;

    if ( $search == null ) {
        $search = array(
            chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7), chr(8),
            chr(11), chr(12), chr(14), chr(15), chr(16), chr(17), chr(18), chr(19),
            "☺","☻","♥","♦","♣","♠","•","◘","○","♂","♀","♪","♫","☼","►","◄",
            "↕","‼"
        );
    }

    $trimed_value   = trim($value);
    $replaced_value = str_replace($search, ' ', $trimed_value);
    $stripped_value = strip_tags($replaced_value);
    $value = htmlentities($stripped_value, ENT_QUOTES, 'UTF-8');
}

function filter_chars ( &$value, $key = null )
{
    static $search;

    if ( $search == null ) {
        $search = array(
            chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7), chr(8),
            chr(11), chr(12), chr(14), chr(15), chr(16), chr(17), chr(18), chr(19),
            "☺","☻","♥","♦","♣","♠","•","◘","○","♂","♀","♪","♫","☼","►","◄",
            "↕","‼"
        );
    }

    $value = str_replace($search, ' ', $value);
}
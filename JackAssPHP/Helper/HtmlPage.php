<?php

/**
 * Mesas Loot Manager
 * Copyright (C) 2011  Mesa <Daniel Langemann>
 *
 * Php version 5.3
 *
 * @category Helper
 * @package  Mesas_Loot_Manager
 * @author   Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Helper;

/**
 * Provides Container for areas of the website and prepares
 * meta data, etc...
 *
 * Php version 5.3
 *
 * @category System_Configuration
 * @package  JackAssPHP
 * @author   Mesa <daniel.langemann@gmx.de>
 */
class HtmlPage
{

    protected $meta = array();

    /**
     * Generate the HTML from an array containing meta data
     * author => Mesa
     *
     * @param [Array] $metaData Array containing metadata
     *
     * @return [String]
     */
    public function getMetaData ()
    {
        $tmp = null;
        foreach ($this->meta as $meta) {
            $tmp .= "        <meta";
            foreach ($meta as $key => $value) {
                $tmp .= " " . $key . "=\"$value\"";
            }
            $tmp .= ">\n";
        }
        $this->meta = array();
        return $tmp;
    }

    /**
     * Add Metadata to the array. The output/ get method is getMetaData
     *
     *
     * @param [ARRAY] $meta attrib ==> value
     *
     * @return void
     */
    public function addMeta ( $meta )
    {
        if (is_array($meta)) {
            array_push($this->meta, $meta);
        }
    }
    /**
     *
     * @todo Pfui!!!! dafür gibt es doch jetzt die Factory
     */
    static public function getInstance ()
    {
        static $instance;
        if (!is_object($instance)) {
            $instance = new HtmlPage();
        }
        return $instance;
    }

}
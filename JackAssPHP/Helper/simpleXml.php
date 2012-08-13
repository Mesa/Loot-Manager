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
 * Reads XML data from file and returns an array
 */
class simpleXml
{

    protected $data = null;

    public function __construct ( $path_to_file )
    {
        if (file_exists($path_to_file)) {
            $this->data = simplexml_load_file($path_to_file);
        }
    }

    protected function walkArray ( $data )
    {
        $array = array();

        foreach ($data as $node) {
            $tmp_data = null;
            $tmp = null;

            if ($node->count() >= 1) {
                $tmp_data = $this->walkArray($node->children());
            } else {
                $tmp_data = (string) $node;
            }

            if (isset($array[$node->getName()])) {
                if (isset($array[$node->getName()][0])
                    && is_array($array[$node->getName()])
                ) {
                    /**
                     * Wenn bereits ein Array existiert, dieses übernehmen
                     */
                    foreach ($array[$node->getName()] as $var) {
                        $tmp[] = $var;
                    }
                } else {
                    /**
                     * es existiert bereits EIN Datensatz, aber kein Array
                     * also erstellen wir ein neues und fügen den verhandenen
                     * Datensatz ein
                     */
                    $tmp[] = $array[$node->getName()];
                }
                $tmp[] = $tmp_data;

                $array[$node->getName()] = array();
                $array[$node->getName()] = $tmp;
            } else {
                $array[$node->getName()] = $tmp_data;
            }
        }
        return $array;
    }

    public function getArray ()
    {
        if ($this->data !== null) {
            return $this->walkArray($this->data);
        } else {
            return false;
        }
    }

}
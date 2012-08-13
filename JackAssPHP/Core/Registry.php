<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Core;

/**
 * Organize a global registry.
 */
class Registry
{

    /**
     *
     * @var [Array] contains all Registry Data in an Array
     */
    private $_config = array();

    /**
     * Set value in registry. you can add a single value or an array
     *
     * @param [String/Array] $field_name Identifier (could be String or Array)
     * @param [Mixed]        $value      Value (not required)
     *
     * @return void
     */
    public function set ( $field_name, $value )
    {
        $this->_config[$field_name] = $value;
    }

    /**
     * Get data from Registry.
     *
     * If Array is given, the same Registry array is returned. Example:
     * $field_name["routes"]["special"] will return data from private
     * $this->data["routes"]["special"] array.
     *
     * @param [String/Array] $field_name identifier
     *
     * @return [Mixed]
     */
    public function get ( $field_name )
    {
        if (!isset($this->_config[$field_name])) {
            return null;
        } else {
            return $this->_config[$field_name];
        }
    }
}
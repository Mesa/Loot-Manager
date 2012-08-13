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
 * Handle the DocComment and Reflection class easily.
 *
 * @category HELPER
 * @package  JackAssPHP
 * @author   Mesa <daniel.langemann@gmx.de>
 */
class TotalReflection
{

    protected $class_name = null;
    protected $method_name = null;
    protected $class_reflection = null;
    protected $method_reflection = null;
    protected $class_atr_list = array();
    protected $method_atr_list = array();
    protected $tag_prefix = "\@";
    protected $tag_postfix = "";

    /**
     * Constructor
     *
     * @param [String] $class_name  Class name
     * @param [String] $method_name Method name (Optional)
     */
    public function __construct ( $class_name, $method_name = null )
    {
        $this->setClass($class_name);

        if ( $method_name !== null ) {
            $this->setMethod($method_name);
        }
    }

    /**
     * Setter for Prefix
     *
     * @param [String] $prefix Prefix to identify tags names
     *
     * @return void
     */
    public function setTagPrefix ( $prefix )
    {
        $this->tag_prefix = $prefix;
    }

    /**
     * Setter for Postfix
     *
     * @param [String] $postfix Postfix to identify tag names
     *
     * @return void
     */
    public function setTagPostfix ( $postfix )
    {
        $this->tag_postfix = $postfix;
    }

    /**
     * Setter for method name
     *
     * @param [String] $method_name Method name
     *
     * @return TotalReflection
     */
    public function setMethod ( $method_name )
    {
        $this->method_atr_list = null;
        $this->method_name = $method_name;
        return $this;
    }

    /**
     * Setter for class name
     *
     * @param [String] $class_name Class name
     *
     * @return TotalReflection
     */
    public function setClass ( $class_name )
    {
        $this->class_atr_list = null;
        $this->class_name = $class_name;
        return $this;
    }

    /**
     * Get a single attribute from DocComment
     *
     * @param [String] $attr Attribute name
     *
     * @return [String] The Attribute value
     */
    public function getClassAttr ( $attr )
    {
        if ( $this->class_atr_list == null ) {
            $this->createClassArray();
        }

        if ( isset($this->class_atr_list[$attr]) ) {
            return $this->class_atr_list[$attr];
        } else {
            return null;
        }
    }

    /**
     * Get a single Attribute from DocComment
     *
     * @param [String] $attr Attribute name
     *
     * @return [String] Attribute value
     */
    public function getMethodAttr ( $attr )
    {
        if ( $this->method_atr_list == null ) {
            $this->createMethodArray();
        }

        if ( isset($this->method_atr_list[$attr]) ) {
            return $this->method_atr_list[$attr];
        } else {
            return null;
        }
    }

    /**
     * Getter for all Class DocComment tags with values as an array
     *
     * @return [Array]
     */
    public function getClassArray ()
    {
        if ( $this->class_atr_list == null ) {
            $this->createClassArray();
        }
        return $this->class_atr_list;
    }

    /**
     * Getter for all Method DocComment tags with values as an array
     *
     * @return [Array]
     */
    public function getMethodArray ()
    {
        if ( $this->method_atr_list == null ) {
            $this->createMethodArray();
        }
        return $this->method_atr_list;
    }

    /**
     * create Reflection class object an get DocComment.
     *
     * @return void
     */
    protected function createClassArray ()
    {
        $this->class_reflection = new \ReflectionClass($this->class_name);

        $doc_comment = $this->class_reflection->getDocComment();
        $this->class_atr_list = $this->createArray($doc_comment);
    }

    /**
     * create Reflection method object an get DocComment.
     *
     * @return void
     */
    protected function createMethodArray ()
    {
        $this->method_reflection = new \ReflectionMethod(
            $this->class_name,
            $this->method_name
        );

        $doc_comment = $this->method_reflection->getDocComment();
        $this->method_atr_list = $this->createArray($doc_comment);
    }

    /**
     * Create value array from DocComment string
     *
     * @param [String] $string DocComment string
     *
     * @return void
     */
    protected function createArray ( $string )
    {
        preg_match_all('/\*+\s' . $this->tag_prefix . '([\w\d]+)' . $this->tag_postfix . '\s(.+)\n/', $string, $matches);

        $tags = $matches[1];
        $values = $matches[2];

        $array = array();

        for ( $count = 0; $count < count($tags); $count++ ) {
            $array[$tags[$count]] = trim($values[$count]);
        }

        return $array;
    }

}
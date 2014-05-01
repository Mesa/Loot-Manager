<?php
namespace Commander\Annotation;


/**
 * @Annotation
 */
class Route
{
    public $name = false;
    public $path = false;
    public $method = "GET|POST";
}

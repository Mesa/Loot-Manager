<?php

namespace JackAssPHP\Core;

require_once dirname(__FILE__) . '/../../../JackAssPHP/Core/Registry.php';
require_once dirname(__FILE__) . '/../../../JackAssPHP/Core/Router.php';

/**
 * Test class for Router.
 * Generated by PHPUnit on 2012-02-06 at 20:45:23.
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Router
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp ()
    {
        $this->object = new Router;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown ()
    {
        
    }

}

?>

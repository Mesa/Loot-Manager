<?php

namespace App\Controller;

use \Commander\Annotation\Route;
use \Commander\Annotation\View;

/**
 * Class CustomController
 *
 * @package App\Controller
 */
class CustomController
{
    /**
     * @Route(name="frontpageOld", path="/123123")
     * @View("test2.php")
     */
    public function frontPageAction()
    {
        return ["test" => "frontpage"];
    }

    /**
     * @Route(name="test", path="/[*:test]")
     * @View("test2.php")
     */
    public function testAction($test)
    {
        return ["test" => "more" . $test];
    }
}

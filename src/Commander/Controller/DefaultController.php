<?php

namespace Commander\Controller;

use \Commander\Annotation\Route;
use \Commander\Annotation\View;

/**
 * Class Controller
 * @Route(path="/main")
 * @package Commander\Controller
 */
class DefaultController
{

    /**
     * @Route(name="install", path="/install")
     * @View("msg.twig.php")
     */
    public function installAction()
    {
        return ["install"];
    }
}

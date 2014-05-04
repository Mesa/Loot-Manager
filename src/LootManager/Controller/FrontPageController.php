<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace LootManager\Controller;

use \Commander\Annotation\Role;
use \Commander\Annotation\Route;
use \Commander\Annotation\View;

class FrontPageController
{

    /**
     * @Inject("\Commander\Annotation\Role")
     */
    public $role;
    /**
     * @Route(name="frontpage", path="/")
     * @View("frontpage.html.twig")
     * @Role("GuestRole")
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route(name="impressum", path="/impressum.html")
     * @View("impressum.html.twig")
     */
    public function impressumAction()
    {
        return [];
    }
}

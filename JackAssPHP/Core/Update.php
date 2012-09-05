<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Core;

class Update extends Controller
{

    protected $helper = null;

    public function __construct ()
    {
        $this->loadSystem();
        $this->registry = \Factory::getRegistry();
        $this->rights = \Factory::getRights();

        if ($this->rights->hasRight("run_system_update")) {
        echo "Update gestartet<br><hr>";
            $install = new \JackAssPHP\Helper\Installer(
                "Application/Config/update.xml", \Factory::getDB()
            );
        echo "<br><hr> Update fertig";
        } else {
            /**
             * @todo throw exception
             */
            header("Location: " . $this->registry->get("WEB_ROOT"));
            echo "Du hast keine Rechte für ein Update";
        }
    }

    public function index ( $args = null )
    {

    }

}

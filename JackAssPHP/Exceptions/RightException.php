<?php

/**
 * Description of NoRightsException
 *
 * @author mesa
 */
class RightException extends \Exception
{

    public function __construct ( $msg )
    {
        parent::__construct();
        $registry = \Factory::getRegistry();
        $lang     = \Factory::getTranslate();
        $html = "<h1>Du kommst hier net rein</h1>\n";
        $html.= "<p>Fehlendes Recht:<br><b style=\"color:steelblue\">" . htmlentities($lang->translate($msg)) . "</b></p>";
        $html.= "Zur&uuml;ck zu Startseite <a href=\"" . $registry->get("WEB_ROOT") . "\">Startseite</a>";
        $this->message = $html;
    }

}

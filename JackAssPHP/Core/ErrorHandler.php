<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

function JackAssErrorHandler ( $fehlercode, $fehlertext, $fehlerdatei, $fehlerzeile )
{
    switch ($fehlercode) {
        case E_USER_ERROR:
            exit(1);
            break;

        case E_USER_WARNING:
            break;

        case E_USER_NOTICE:
            break;

        default:
            break;
    }

    /* Damit die PHP-interne Fehlerbehandlung nicht ausgeführt wird */
    return true;
}
<?php

/**
 * Mesas Loot Manager
 * Copyright (C) 2012  Mesa <Daniel Langemann>
 *
 * @category Exception
 * @package  Mesas_Loot_Manager
 * @author   Mesa <daniel.langemann@gmx.de>
 */
function DefaultException ( $exception )
{
    echo "[Exception]:".$exception->getMessage();
}
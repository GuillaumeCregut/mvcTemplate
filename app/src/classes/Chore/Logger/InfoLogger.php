<?php

namespace Editiel98\Chore\Logger;

/**
 * logger for infos
 */
class InfoLogger extends Logger
{
    public function __construct()
    {
        parent::__construct('info');
    }
}

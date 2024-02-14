<?php

namespace Editiel98\Kernel\Logger;

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

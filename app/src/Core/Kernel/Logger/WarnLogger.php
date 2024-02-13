<?php

namespace Editiel98\Kernel\Logger;

/**
 * Logger for warnings
 */
class WarnLogger extends Logger
{
    public function __construct()
    {
        parent::__construct('warn');
    }
}

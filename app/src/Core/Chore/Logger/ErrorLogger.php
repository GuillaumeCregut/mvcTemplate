<?php

namespace Editiel98\Chore\Logger;

/**
 * Logger for errors
 */
class ErrorLogger extends Logger
{
    public function __construct()
    {
        parent::__construct('error');
    }
}

<?php

namespace Editiel98\Kernel;

use Editiel98\Templates\DebugSmarty;

class MainAbstractController
{
    protected DebugSmarty $smarty;

    public function __construct()
    {
        $this->smarty = new DebugSmarty();
    }
}

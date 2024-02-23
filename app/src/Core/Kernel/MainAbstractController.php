<?php

namespace Editiel98\Kernel;

use Editiel98\Kernel\WebInterface\ResponseHandler;
use Editiel98\Templates\DebugSmarty;

class MainAbstractController
{
    protected DebugSmarty $smarty;

    public function __construct()
    {
        $this->smarty = new DebugSmarty();
    }

    /**
     * @param string $name
     * @param mixed[]|null $values
     *
     * @return ResponseHandler
     */
    public function render(string $name, ?array $values = []): ResponseHandler
    {
        foreach ($values as $key => $value) {
            $this->smarty->assignVar($key, $value);
        }
        $template = $this->smarty->fetchTemplate($name);
        $rhandler = new ResponseHandler();
        $rhandler->setContent($template);
        return $rhandler;
    }
}

<?php

namespace Editiel98\Templates;

use Editiel98\App;
use Editiel98\Kernel\Attribute\RouteAttribute;
use Editiel98\Kernel\MainAbstractController;
use Editiel98\Kernel\Routing\RoutesDisplay;

#[RouteAttribute('/debug', name: 'debug_')]
class DebugController extends MainAbstractController
{
    public function getDisplay(): string
    {
        $timeSpent = round(1000 * (microtime(true) - App::$timeStart));
        $this->smarty->assignVar('time', $timeSpent);
        return $this->smarty->fetchTemplate('debug.tpl');
    }

    #[RouteAttribute('/routes', name: 'routes')]
    public function displayToto(): void
    {
        $displayRoutes = new RoutesDisplay();
        $this->smarty->assign('routes', $displayRoutes->getRoutes());
        $this->smarty->assign('debug', $this->getDisplay());
        $this->smarty->displayTemplate('debug/pages.tpl');
    }
}

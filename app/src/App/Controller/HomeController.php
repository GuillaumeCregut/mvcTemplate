<?php

namespace App\Controller;

use Editiel98\AbstractController;
use Editiel98\Kernel\Attribute\RouteAttribute;
use Editiel98\Kernel\Routing\RoutesDisplay;

class HomeController extends AbstractController
{
    #[RouteAttribute('/', name: 'home')]
    public function index(): void
    {
        $toto=new RoutesDisplay();
        var_dump($toto->getRoutes());
        $this->smarty->display('_base.tpl');
    }
}

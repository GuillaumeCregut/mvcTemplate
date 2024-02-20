<?php

namespace App\Controller;

use Editiel98\AbstractController;
use Editiel98\Kernel\Attribute\RouteAttribute;
use Editiel98\Kernel\Routing\RoutesDisplay;
use Editiel98\Templates\DebugController;

class HomeController extends AbstractController
{
    #[RouteAttribute('/', name: 'home')]
    public function index(): void
    {
        $this->render('_base.tpl');
    }
}

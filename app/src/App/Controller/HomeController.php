<?php

namespace App\Controller;

use Editiel98\AbstractController;
use Editiel98\Kernel\Attribute\RouteAttribute;

class HomeController extends AbstractController
{
    #[RouteAttribute('/', name: 'home')]
    public function index(): void
    {
        $this->render('_base.tpl');
    }
}

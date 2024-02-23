<?php

namespace App\Controller;

use Editiel98\AbstractController;
use Editiel98\Kernel\Attribute\RouteAttribute;
use Editiel98\Kernel\WebInterface\ResponseHandler;

class HomeController extends AbstractController
{
    #[RouteAttribute('/', name: 'home')]
    public function index(): ResponseHandler
    {
        return $this->render('_base.tpl');
    }
}

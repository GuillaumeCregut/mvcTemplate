<?php
namespace App\Controller;

use Editiel98\AbstractController;
use Editiel98\Kernel\Attribute\RouteAttribute;
use Editiel98\Kernel\Routing\RoutesDisplay;

class HomeController extends AbstractController
{
    #[RouteAttribute('/', name:'home')]
    public function index(): void
    {
        $router=new RoutesDisplay();
        $this->smarty->display('_base.tpl');
    }
}
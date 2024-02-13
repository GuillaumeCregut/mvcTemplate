<?php
namespace App\Controller;

use Editiel98\AbstractController;
use Editiel98\Kernel\Attribute\RouteAttribute;

class HomeController extends AbstractController
{
    #[RouteAttribute('/', name:'home')]
    public function index(): void
    {
        $this->smarty->display('_base.tpl');
    }
}
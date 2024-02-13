<?php
namespace App\Controller;

use Editiel98\AbstractController;
use Editiel98\Chore\Attribute\RouteAttribute;

class HomeController extends AbstractController
{
    #[RouteAttribute('/')]
    public function index(): void
    {
       $this->smarty->display('_base.tpl');
    }
}
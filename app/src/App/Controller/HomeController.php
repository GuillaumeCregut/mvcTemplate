<?php
namespace App\Controller;

use Editiel98\AbstractController;


class HomeController extends AbstractController
{
    public function index(): void
    {
       $this->smarty->display('_base.tpl');
    }
}
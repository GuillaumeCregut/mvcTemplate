<?php
namespace App\Controller;

use Editiel98\AbstractController;


class HomeController extends AbstractController
{
    public function index(?array $params=[])
    {
        echo "Hello";
        var_dump($params);
    }
}
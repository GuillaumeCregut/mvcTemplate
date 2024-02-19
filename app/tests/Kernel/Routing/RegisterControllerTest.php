<?php

use Editiel98\Kernel\Routing\RegisterController;
use PHPUnit\Framework\TestCase;

class RegisterControllerTest extends TestCase
{
    public function testGetController(): void
    {
        $controller=RegisterController::getControllers();
        $this->assertIsArray($controller);
    }

    public function testGetRoutes(): void
    {
        $routes=RegisterController::getRoutes();
        $this->assertIsArray($routes);
    }
}
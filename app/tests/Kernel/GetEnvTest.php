<?php

use Editiel98\Kernel\GetEnv;
use PHPUnit\Framework\TestCase;

class GetEnvTest extends TestCase
{

    public function testGetEnvWithoutParam(): void
    {
        $x = GetEnv::getEnvValue('');
        $this->assertEquals('', $x);
    }

    public function testGetEnvWithWrongParam(): void
    {
        $x = GetEnv::getEnvValue('Hello');
        $this->assertEquals('', $x);
    }

    public function testGetEnvWithOKParam(): void
    {
        $x = GetEnv::getEnvValue('envMode');
        $this->assertMatchesRegularExpression('/[a-zA-Z0-9]+/', $x);
    }
}

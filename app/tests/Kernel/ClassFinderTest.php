<?php

use Editiel98\Kernel\ClassFinder;
use PHPUnit\Framework\TestCase;

class ClassFinderTest extends TestCase
{
    public function testGetClassesWithoutClassName(): void
    {
        $this->expectException(\Exception::class);
        ClassFinder::getClassesInNamespace('');
    }

    public function testGetClassesWithwrongClassName(): void
    {
        $this->expectException(\Exception::class);
        ClassFinder::getClassesInNamespace('Editiel98\Toto');
    }

    public function testGetClassOK(): void
    {
        $x=ClassFinder::getClassesInNamespace('Editiel98\\Kernel');
        $this->assertIsArray($x);
    }
}

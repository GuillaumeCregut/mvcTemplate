<?php

use Editiel98\Kernel\Attribute\Constraints\URLConstraint;
use PHPUnit\Framework\TestCase;

class URLConstraintTest extends TestCase
{
    public function testWithBlank(): void
    {
        $constraint=new URLConstraint();
        $this->assertFalse($constraint->isOK(''));
    }

    public function testWithBadURL(): void
    {
        $constraint=new URLConstraint();
        $this->assertFalse($constraint->isOK('httdfp://www.gdsfdsf'));
    }

    public function testWithGoodURL(): void
    {
        $constraint=new URLConstraint();
        $this->assertTrue($constraint->isOK('http://www.google.com'));
    }

    public function testWithGoodURLWithPath(): void
    {
        $constraint=new URLConstraint();
        $this->assertTrue($constraint->isOK('http://www.google.com/bonjour/Hello'));
    }

    public function testWithGoodURLWithFile(): void
    {
        $constraint=new URLConstraint();
        $this->assertTrue($constraint->isOK('http://www.google.com/bonjour/Hello.txt'));
    }
    public function testWithGoodURLWithQuery(): void
    {
        $constraint=new URLConstraint();
        $this->assertTrue($constraint->isOK('http://www.google.com/bonjour/Hello?a=1&b=2'));
    }
}
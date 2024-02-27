<?php

use Editiel98\Kernel\Attribute\Constraints\NotBlankConstraint;
use PHPUnit\Framework\TestCase;

class NotBlankTest extends TestCase
{
    public function testWithBlank(): void
    {
        $constraint=new NotBlankConstraint();
        $this->assertFalse($constraint->isOK(''));
    }

    public function testWithNotBlankValue(): void
    {
        $constraint=new NotBlankConstraint();
        $this->assertTrue($constraint->isOK('Hello'));
    }

    public function testWithFalseValue(): void
    {
        $constraint=new NotBlankConstraint();
        $this->assertTrue($constraint->isOK(false));
    }

    public function testWithTrueValue(): void
    {
        $constraint=new NotBlankConstraint();
        $this->assertTrue($constraint->isOK(true));
    }

    public function testWithNumberValue0(): void
    {
        $constraint=new NotBlankConstraint();
        $this->assertTrue($constraint->isOK(0));
    }
}
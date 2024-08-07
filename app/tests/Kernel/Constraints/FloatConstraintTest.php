<?php

use Editiel98\Kernel\Attribute\Constraints\FloatConstraint;
use PHPUnit\Framework\TestCase;

class FloatConstraintTest extends TestCase
{
    public function testWitNoData(): void
    {
        $constraint=new FloatConstraint();
        $this->assertFalse($constraint->isOK(''));
    }

    public function testWithFloatString(): void
    {
        $constraint=new FloatConstraint();
        $this->assertTrue($constraint->isOK('123.45'));
    }

    public function testWithFloat(): void
    {
        $constraint=new FloatConstraint();
        $this->assertTrue($constraint->isOK(123.45));
    }

    public function testWithInteger(): void
    {
        $constraint=new FloatConstraint();
        $this->assertFalse($constraint->isOK(123));
    }

    public function testWithIntegerString(): void
    {
        $constraint=new FloatConstraint();
        $this->assertTrue($constraint->isOK('123'));
    }
}
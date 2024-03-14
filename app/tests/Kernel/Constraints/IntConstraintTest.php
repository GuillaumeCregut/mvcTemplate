<?php

use Editiel98\Kernel\Attribute\Constraints\IntConstraint;
use PHPUnit\Framework\TestCase;

class IntConstraintTest extends TestCase
{
    public function testWithBlank(): void
    {
        $constraint=new IntConstraint();
        $this->assertFalse($constraint->isOK(''));
    }

    public function testConstraintNotInt(): void
    {
        $constraint=new IntConstraint();
        $this->assertFalse($constraint->isOK('arezrzr'));
    }

    public function testConstraintWithInt(): void
    {
        $constraint=new IntConstraint();
        $this->assertTrue($constraint->isOK(0));
    }

    public function testConstraintStringInt(): void
    {
        $constraint=new IntConstraint();
        $this->assertTrue($constraint->isOK('0'));
    }

    public function testConstraintWithFloat(): void
    {
        $constraint=new IntConstraint();
        $this->assertFalse($constraint->isOK(1.234));
    }
}
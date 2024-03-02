<?php

use Editiel98\Kernel\Attribute\Constraints\BoolConstraint;
use PHPUnit\Framework\TestCase;

class BoolConstraintTest extends TestCase
{
    public function testWithoutValue(): void
    {
        $constraint = new BoolConstraint();
        $this->assertTrue($constraint->isOK(null));
    }

    public function testWithStringValue(): void
    {
        $constraint = new BoolConstraint();
        $this->assertFalse($constraint->isOK('toto'));
    }

    public function testWithBoolValue(): void
    {
        $constraint = new BoolConstraint();
        $this->assertTrue($constraint->isOK(true));
    }

    public function testWithStringOnValue(): void
    {
        $constraint = new BoolConstraint();
        $this->assertTrue($constraint->isOK('on'));
    }
}

<?php

use Editiel98\Kernel\Attribute\Constraints\StringConstraint;
use PHPUnit\Framework\TestCase;

class StringConstraintTest extends TestCase
{
    public function testConstraintNotString(): void
    {
        $constraint=new StringConstraint();
        $this->assertFalse($constraint->isOK(0));
    }
    public function testConstraintString(): void
    {
        $constraint=new StringConstraint();
        $this->assertTrue($constraint->isOK('Bonjour'));
    }
}
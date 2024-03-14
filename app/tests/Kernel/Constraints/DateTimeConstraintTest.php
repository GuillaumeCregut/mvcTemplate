<?php

use Editiel98\Kernel\Attribute\Constraints\DateTimeConstraint;
use PHPUnit\Framework\TestCase;

class DateTimeConstraintTest extends TestCase
{
    public function testWithBadDateTime(): void
    {
        $constraint=new DateTimeConstraint();
        $dateTime='2002-22-15 15:56';
        $this->assertFalse($constraint->isOK( $dateTime));
    }
    public function testWithOKDateTime(): void
    {
        $constraint=new DateTimeConstraint();
        $dateTime='2002-22-15T15:56';
        $this->assertFalse($constraint->isOK( $dateTime));
    }
}
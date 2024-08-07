<?php

use Editiel98\Kernel\Attribute\Constraints\DateConstraint;
use PHPUnit\Framework\TestCase;

class DateConstraintTest extends TestCase
{
    public function testWithBadDate(): void
    {
        $constraint=new DateConstraint();
        $dateTime='2002-22-15 15:56';
        $this->assertFalse($constraint->isOK( $dateTime));
    }
    public function testWithNotOKDate(): void
    {
        $constraint=new DateConstraint();
        $dateTime='2002-12-45';
        $this->assertFalse($constraint->isOK( $dateTime));
    }
    public function testWithOKDate(): void
    {
        $constraint=new DateConstraint();
        $dateTime='2002-12-15';
        $this->assertTrue($constraint->isOK( $dateTime));
    }
}
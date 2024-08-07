<?php

use Editiel98\Forms\Pacificator;
use PHPUnit\Framework\TestCase;

class PacificatorTest extends TestCase
{
    public function testStringWithOKValues(): void
    {
        $string="Bonjour Monde";
        $result=Pacificator::pacifyString($string);
        $this->assertEquals($string,$result);
    }

    public function testStringWithAccentValues(): void
    {
        $string="Guillaume Crégut";
        $result=Pacificator::pacifyString($string);
        $this->assertEquals($string,$result);
    }

    public function testStringWithSpecialValues(): void
    {
        $string="<script>Guillaume Crégut</script>";
        $result=Pacificator::pacifyString($string);
        $this->assertStringNotContainsString('<script>',$result);
    }
    public function testStringWithQuotes(): void
    {
        $string="Guillaume' Crégut";
        $result=Pacificator::pacifyString($string);
        $this->assertEquals($string,$result);
    }

    public function testIntegerWithoutIntegerValues(): void
    {
        $integer='a';
        $result=Pacificator::pacifyInteger($integer);
        $this->assertNull($result);
    }

    public function testIntegerWithStringInteger(): void
    {
        $integer='123';
        $result=Pacificator::pacifyInteger($integer);
        $this->assertEquals(123,$result);
    }

    public function testBoolWithWrongValues(): void
    {
        $bool='a';
        $result=Pacificator::pacifyBool($bool);
        $this->assertNull($result);
    }

    public function testBoolWithTrueValues(): void
    {
        $bool=true;
        $result=Pacificator::pacifyBool($bool);
        $this->assertEquals(true,$result);
    }

    public function testFloatWithWrongValues(): void
    {
        $float='a';
        $result=Pacificator::pacifyFloat($float);
        $this->assertNull($result);
    }

    public function testFloatWithIntegerValues(): void
    {
        $float=123;
        $result=Pacificator::pacifyFloat($float);
        $this->assertEquals(123.0,$result);
    }

    public function testFloatWithStringFloatValues(): void
    {
        $float='123.456';
        $result=Pacificator::pacifyFloat($float);
        $this->assertEquals(123.456,$result);
    }
}
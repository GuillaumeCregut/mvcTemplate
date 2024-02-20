<?php

use Editiel98\Kernel\Exception\WebInterfaceException;
use Editiel98\Kernel\WebInterface\ReadWriteContainer;
use PHPUnit\Framework\TestCase;

class ReadWriteInterfaceTest extends TestCase
{
    private array $var=[
        'test'=>12,
        'essai'=>[
            'a'=>458,
            'b'=>'Bonjour'
        ],
        'key'=>'value'
    ];
    private ReadWriteContainer $readWrite;

   
    public function setUp(): void
    {
        $this->readWrite=new ReadWriteContainer($this->var);
    }
    
    public function testConstructor(): void
    {
        $this->assertArrayHasKey('test',$this->readWrite->getAll());
    }

    public function testGetParamWithWrongKey(): void
    { 
        $this->expectException(WebInterfaceException::class);
        $x=$this->readWrite->getParam('bala');
    }

    public function testGetParam(): void
    {
        $x=$this->readWrite->getParam('test');
        $this->assertEquals(12,$x);
    }

    public function testCkear(): void
    {
        $this->readWrite->clear();
        $this->assertEmpty($this->readWrite->getAll());
    }

    public function testAsKeyWrong(): void
    {
        $this->assertEquals(false,$this->readWrite->hasKey('toto'));
    }

    public function testAsKeyOK(): void
    {
        $this->assertEquals(true,$this->readWrite->hasKey('test'));
    }

    public function testRemoveWrong(): void
    {
        $this->readWrite->remove('toto');
        $this->assertEquals(false,$this->readWrite->hasKey('toto'));
    }

    public function testRemoveOk(): void
    {
        $this->readWrite->remove('test');
        $this->assertEquals(false,$this->readWrite->hasKey('test'));
    }

    public function testAddKey(): void
    {
        $this->readWrite->setValue('toto',123);
        $this->assertEquals(123,$this->readWrite->getParam('toto'));
    }
}
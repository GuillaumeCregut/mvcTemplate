<?php

use Editiel98\Kernel\Exception\WebInterfaceException;
use Editiel98\Kernel\WebInterface\RequestHandler;
use Editiel98\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    private RequestHandler $handler;
   
    public function setUp(): void
    {
        $this->handler=RequestHandler::getInstance();
        $this->handler->init([],[],[],[],[]);
    }

    public function testGetKeyWithWrongKey(): void
    {
        $session=new Session();
        $key=$session->getKey('toto');
        $this->assertEquals(null,$key);
    }

    public function testGetKeyWithOKKey(): void
    {
        $this->handler->session->setValue('test','Bonjour');
        $session=new Session();
        $key=$session->getKey('test');
        $this->assertEquals('Bonjour',$key);
    }

    public function testSetKey(): void
    {
        $session=new Session();
        $session->setKey('test', 'Bonjour');
        $key=$session->getKey('test');
        $this->assertEquals('Bonjour',$key);
        $this->assertEquals('Bonjour', $this->handler->session->getParam('test'));
    }

    public function testRemoveKey(): void
    {
        $this->handler->session->setValue('test','Bonjour');
        $session=new Session();
        $key=$session->deleteKey('test');
        $this->assertEquals(null,$key);
        $this->expectException(WebInterfaceException::class);
        $this->handler->session->getParam('test');
    }
}
<?php

use Editiel98\Kernel\CSRFCheck;
use Editiel98\Kernel\WebInterface\RequestHandler;
use Editiel98\Session;
use PHPUnit\Framework\TestCase;

class CsrfCheckTest extends TestCase
{
    private RequestHandler $handler;
   
    public function setUp(): void
    {
        $this->handler=RequestHandler::getInstance();
        $this->handler->init([],[],[],[],[]);
    }
    
    public function testSetToken(): void
    {
        $session=new Session();
        $csrf=new CSRFCheck($session);
        $token=$csrf->createToken();
        $tokenKey=$session->getKey('token_part');
        $this->assertIsString($token);
        $this->assertIsString($tokenKey);
    }

    public function testGetTokenWithoutInit(): void
    {
        $session=new Session();
        $csrf=new CSRFCheck($session);
        $token=$csrf->checkToken('');
        $this->assertEquals(false,$token);
    }

    public function testGetTokenWithoutToken(): void
    {
        $session=new Session();
        $csrf=new CSRFCheck($session);
        $token=$csrf->createToken();
        $token=$csrf->checkToken('');
        $this->assertEquals(false,$token);
    }

    public function testGetTokenWithWrongToken(): void
    {
        $session=new Session();
        $csrf=new CSRFCheck($session);
        $token=$csrf->createToken();
        $token=$csrf->checkToken('123456');
        $this->assertEquals(false,$token);
    }

    public function testGetTokenWithOKToken(): void
    {
        $session=new Session();
        $csrf=new CSRFCheck($session);
        $token=$csrf->createToken();
        $tokenResult=$csrf->checkToken($token);
        $this->assertEquals(true,$tokenResult);
    }
}
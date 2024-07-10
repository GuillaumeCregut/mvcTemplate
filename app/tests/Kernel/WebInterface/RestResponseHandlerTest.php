<?php

use PHPUnit\Framework\TestCase;
use Editiel98\Kernel\WebInterface\RequestHandler;
use Editiel98\Kernel\WebInterface\RestResponseHandler;

class RestResponseHandlerTest extends TestCase
{
    private RequestHandler $handler;
    private RestResponseHandler $response;

    public function setUp(): void
    {
        $this->handler = RequestHandler::getInstance();
        $this->handler->init([], [], [], [], [],[]);
        $this->response = new RestResponseHandler();
    }

    public function testSetHeaderResponse(): void
    {
        $this->response->setHeaderResponse(500, 'Internal Server');
        $this->assertEquals(500, $this->response->getStatusCode());
        $this->assertEquals('Internal Server', $this->response->getStatusText());
    }

    public function testSendHeadersWithoutCode(): void
    {
        $this->response->sendHeaders();
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    public function testSendHeadersWithCode(): void
    {
        $this->response->sendHeaders(300);
        $this->assertEquals(300, $this->response->getStatusCode());
    }

    public function testSendHeadersWithCodeAndStatus(): void
    {
        $this->response->sendHeaders(302, 'Found');
        $this->assertEquals(302, $this->response->getStatusCode());
        $this->assertEquals('Found', $this->response->getStatusText());
    }

    public function testSetContent(): void
    {
        $this->response->setContent('bonjour');
        $this->assertEquals('bonjour', $this->response->getContent());
    }

    public function testPrepareEmpty(): void
    {
        $this->response->prepare();
        $this->assertEquals('HTTP/1.1',$this->response->getVersion());
        $this->assertEquals('UTF-8',$this->response->getCharset());
        $this->assertArrayHasKey('Date',$this->response->getHeaders()->getHeaders());
    }

    public function testPrepareWithVersionSet(): void
    {
        $this->response->setVersion('HTTP/1.0');
        $this->response->prepare();
        $this->assertEquals('HTTP/1.0',$this->response->getVersion());
        $this->assertEquals('UTF-8',$this->response->getCharset());
        $this->assertArrayHasKey('Date',$this->response->getHeaders()->getHeaders());
    }

    public function testPrepareWithrequest(): void
    {
        $this->handler->init([], [], ['SERVER_PROTOCOL'=>'HTTP/1.0'], [], [],[]);
        $this->response->prepare();
        $this->assertEquals('HTTP/1.0',$this->response->getVersion());
        $this->assertEquals('UTF-8',$this->response->getCharset());
        $this->assertArrayHasKey('Date',$this->response->getHeaders()->getHeaders());
    }


    public function testSendWithContent(): void
    {
        $this->response->prepareJson(['hello'=>'world']);
        $test=$this->response->send();
        $this->assertJson($test);
    }
}

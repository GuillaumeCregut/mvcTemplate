<?php

use Editiel98\Kernel\JwtToken;
use PHPUnit\Framework\TestCase;

class JwtTokenTest extends TestCase
{
    public function testCreateToken()
    {
        $jwt = new JwtToken();
        $token = $jwt->createToken([],'ABCD',5);
        $this->assertIsString($token);
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',$token);
    }

    public function testHeaderToken()
    {
        $jwt = new JwtToken();
        $token = $jwt->createToken([],'ABCD',5);
        $header = $jwt->getHeader($token);
        $this->assertIsArray($header);
    }

    public function testPayloadToken()
    {
        $jwt = new JwtToken();
        $token = $jwt->createToken([],'ABCD',5);
        $payload = $jwt->getPayload($token);
        $this->assertIsArray($payload);
        $this->assertArrayHasKey('iat',$payload);
    }

    public function testVerifyTokenWithWrongFormat()
    {
        $jwt = new JwtToken();   
        $this->expectException(\InvalidArgumentException::class);
        $jwt->checkToken('fsdfsdfqsdfq','');
    }

    public function testIsValidTokenWithWrongDatas()
    {
        $jwt = new JwtToken();
        $this->expectException(\InvalidArgumentException::class);
        $token = $jwt->checkToken('ABC.DEV.TERE','');
    }

    public function testValidTokenWithOKDataButWrongSignature()
    {
        $jwt=new JwtToken();
        $token=$jwt->checkToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJuYW1lIjoiR3VpbGxhdW1lIiwiaWF0IjoxNzEyNjUxMjQ2LCJleHAiOjE3MTI3Mzc2NDZ9.Kbtb1fc9_KEwAhuDrFE4Vw7-CFGhLfVjPvZIg74tV1U','');
        $this->assertFalse($token);
    }
    public function testValidTokenWithOKData()
    {
        $jwt=new JwtToken();
        $jwt2=new JwtToken();
        $jtoken=$jwt->createToken(['name' => 'guillaume'],'ABCDE',5);
        $token=$jwt2->checkToken($jtoken,'ABCDE');
        $this->assertTrue($token);
        $this->assertArrayHasKey('name',$jwt2->getPayload());
    }
    public function testValidTokenUnexpired() {
        $jwt=new JwtToken();
        $jwt2=new JwtToken();
        $jtoken=$jwt->createToken(['name' => 'guillaume'],'ABCDE',2);
        $token=$jwt2->checkToken($jtoken,'ABCDE');
        $this->assertFalse($jwt2->isExpired());
    }
    public function testValidTokenExpired() {
        $jwt=new JwtToken();
        $jwt2=new JwtToken();
        $jtoken=$jwt->createToken(['name' => 'guillaume'],'ABCDE',1);
        sleep(2);
        $token=$jwt2->checkToken($jtoken,'ABCDE');
        $this->assertTrue($jwt2->isExpired());
    }
}
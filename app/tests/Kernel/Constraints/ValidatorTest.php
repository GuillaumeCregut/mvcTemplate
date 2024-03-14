<?php

use Editiel98\Kernel\Attribute\Constraints\BoolConstraint;
use Editiel98\Kernel\Attribute\Constraints\CheckBoxConstraint;
use Editiel98\Kernel\Attribute\Validators\GetConstraints;
use Editiel98\Kernel\Attribute\Validators\Validator;
use Editiel98\Kernel\Exception\ValidationExceptionEmpty;
use PHPUnit\Framework\TestCase;

use Editiel98\Kernel\Attribute\Constraints\NotBlankConstraint;
use Editiel98\Kernel\Entity\AbstractEntity;
use Editiel98\Kernel\WebInterface\RequestHandler;

class EntitySample extends AbstractEntity
{
    #[NotBlankConstraint]
    public string $toto;
    #[CheckBoxConstraint]
    public bool $myBool;

    public string $empty;
    public function getDatasForForm(): array
    {
        return [];
    }
}

class ValidatorTest extends TestCase
{

    public function testValidatorWithoutArray(): void
    {
        $this->expectException(ValidationExceptionEmpty::class);
        Validator::validate([],[]);
    }
    
    public function testValidatorWithoutFields(): void
    {
        $this->expectException(ValidationExceptionEmpty::class);
        Validator::validate([],['1'=>'']);
    }

    public function testValidatorWithoutValidators(): void
    {
       $this->assertFalse(Validator::validate(['1'=>''],[]));
    }

    public function testValidatorWithDifferentKeys(): void
    {
        $handler= RequestHandler::getInstance();
        $handler->init([],[],[],[],[],[]);
        $constraint = GetConstraints::scanEntity(\EntitySample::class);
        $this->assertArrayHasKey('toto',Validator::validate(['Bonjour'=>"sdsdq"],$constraint));
    }

    public function testValidatorWithFieldNoValidate(): void
    {
       
        $this->assertTrue(Validator::validate(['bonjour'=>''],['bonjour'=>[]]));
    }

    public function testValidatorWithFieldNotExistInEntity(): void
    {
        
        $this->assertTrue(Validator::validate(['bonjour'=>'','notPresent'=>''],['bonjour'=>[]]));
    }

    public function testValidatorWithWrongValue(): void
    {
        $handler= RequestHandler::getInstance();
        $handler->init([],[],[],[],[],[]);
        $constraint = GetConstraints::scanEntity(\EntitySample::class);
        $this->assertArrayHasKey('toto',Validator::validate(['toto'=>''],$constraint));
    }

    public function testValidatorWithOKValue(): void
    {
        $handler= RequestHandler::getInstance();
        $handler->init([],[],[],[],[],[]);
        $constraint = GetConstraints::scanEntity(\EntitySample::class);
        $this->assertTrue(Validator::validate(['toto'=>'Bonjour'],$constraint));
    }

    public function testValidatorWithNoValuesButNoContraint(): void
    {
        $handler= RequestHandler::getInstance();
        $handler->init([],[],[],[],[],[]);
        $constraint = GetConstraints::scanEntity(\EntitySample::class);
        $this->assertTrue(Validator::validate(['toto'=>'Bonjour'],$constraint));
    }

    public function testValidatorWithNoValuesButBlankAllowed(): void
    {
        $handler= RequestHandler::getInstance();
        $handler->init([],[],[],[],[],[]);
        $constraint = GetConstraints::scanEntity(\EntitySample::class);
        $this->assertTrue(Validator::validate(['toto'=>'Bonjour'],$constraint));
    }

    public function testValidatorWithOnlyBoolConstraint(): void
    {
        $handler= RequestHandler::getInstance();
        $handler->init([],['toto'=>'Bonjour'],[],[],[],[]);
        $request=$handler->request->getAll();
        $constraint= GetConstraints::scanEntity(\EntitySample::class);
        $this->assertTrue(Validator::validate($request,$constraint));
        $this->assertArrayHasKey('myBool',$handler->request->getAll());
    }
}
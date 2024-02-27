<?php

use Editiel98\Kernel\Attribute\Validators\GetConstraints;
use Editiel98\Kernel\Attribute\Validators\Validator;
use Editiel98\Kernel\Exception\ValidationExceptionEmpty;
use PHPUnit\Framework\TestCase;

use Editiel98\Kernel\Attribute\Constraints\NotBlankConstraint;
use Editiel98\Kernel\Entity\AbstractEntity;

class EntitySample extends AbstractEntity
{
    #[NotBlankConstraint]
    public string $toto;

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
        $constraint = GetConstraints::scanEntity(\EntitySample::class);
        $this->assertArrayHasKey('toto',Validator::validate(['toto'=>''],$constraint));
    }

    public function testValidatorWithOKValue(): void
    {
        $constraint = GetConstraints::scanEntity(\EntitySample::class);
        $this->assertTrue(Validator::validate(['toto'=>'Bonjour'],$constraint));
    }

    public function testValidatorWithNoValuesButNoContraint(): void
    {
        $constraint = GetConstraints::scanEntity(\EntitySample::class);
        $this->assertTrue(Validator::validate(['toto'=>'Bonjour'],$constraint));
    }

    public function testValidatorWithNoValuesButBlankAllowed(): void
    {
        $constraint = GetConstraints::scanEntity(\EntitySample::class);
        $this->assertTrue(Validator::validate(['toto'=>'Bonjour'],$constraint));
    }
}
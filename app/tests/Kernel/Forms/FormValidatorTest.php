<?php

use Editiel98\Forms\FormValidator;
use Editiel98\Kernel\Attribute\Constraints\NotBlankConstraint;
use Editiel98\Kernel\Entity\AbstractEntity;
use PHPUnit\Framework\TestCase;

class EntitySample2 extends AbstractEntity
{
    #[NotBlankConstraint]
    public string $toto;

    public string $empty;
    public function getDatasForForm(): array
    {
        return [];
    }
}

class FormValidatorTest extends TestCase
{
    private FormValidator $formValidator;

    public function setup(): void
    {
        $this->formValidator=new FormValidator();
    }

    public function testValidatorExists(): void
    {
        $this->assertTrue(method_exists($this->formValidator,'validate'));
    }

    public function testValidateNotClass(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->formValidator->validate('toto',[]);
    }

    public function testValidateNotEntity(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->formValidator->validate(self::class,[]);
    }

    public function testValidateNoDatas(): void
    {
        $result=$this->formValidator->validate(\EntitySample2::class,[]);
        $this->assertFalse(($result));
    }

    public function testValidateWithWrongDatas(): void
    {
        $result=$this->formValidator->validate(\EntitySample2::class,['Bonjour'=>'test']);
        $this->assertFalse(($result));
        $this->assertArrayHasKey('toto',$this->formValidator->getErrorInputs());
    }

    public function testValidateWithOKValues(): void
    {
        $result=$this->formValidator->validate(\EntitySample2::class,['toto'=>0]);
        $this->assertTrue(($result));
        $this->assertEmpty($this->formValidator->getErrorInputs());
    }
}
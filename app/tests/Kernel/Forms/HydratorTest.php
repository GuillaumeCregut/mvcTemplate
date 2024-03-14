<?php

use Editiel98\Forms\Hydrator;
use Editiel98\Kernel\Attribute\Constraints\NotBlankConstraint;
use Editiel98\Kernel\Entity\AbstractEntity;
use PHPUnit\Framework\TestCase;

class EntitySample3 extends AbstractEntity
{
    #[NotBlankConstraint]
    public string $toto = '';

    public string $empty = '';

    public int $myInt;

    public function setMyInt(int $myInt): void
    {
        $this->myInt = $myInt;
    }

    public function getMyInt(): int
    {
        return $this->myInt;
    }

    public function setToto(?string $toto): void
    {
        $this->toto = $toto;
    }

    public function getToto(): string
    {
        return $this->toto;
    }

    public function setEmpty(string $empty): void
    {
        $this->empty = $empty;
    }

    public function getEmpty(): string
    {
        return $this->empty;
    }
}

class HydratorTest extends TestCase
{
    private Hydrator $hydrator;

    public function setup(): void
    {
        $this->hydrator = new Hydrator();
    }

    public function testBadClassHydrator(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->hydrator->hydrate('', []);
    }

    public function testEmptyHydrator(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No values provided');
        $this->hydrator->hydrate(\EntitySample3::class, []);
    }

    public function testWithMinimalValues(): void
    {
        $result = $this->hydrator->hydrate(\EntitySample3::class, ['toto' => '123']);
        $this->assertTrue($result instanceof \EntitySample3);
    }

    public function testWithMandatoryValuesSet(): void
    {
        $result = $this->hydrator->hydrate(\EntitySample3::class, ['toto' => '123']);
        if($result instanceof \EntitySample3)
            $this->assertEquals('123', $result->getToto());
    }

    public function testWithAllValuesSet(): void
    {
        $result = $this->hydrator->hydrate(\EntitySample3::class, ['toto' => '123', 'empty' => 'vide']);
        if ($result instanceof \EntitySample3) {
            $this->assertEquals('123', $result->getToto());
            $this->assertEquals('vide', $result->getEmpty());
        }
        $this->assertEmpty($this->hydrator->getNotSetValues());
    }

    public function testWithUnsetValues(): void
    {
        $result = $this->hydrator->hydrate(\EntitySample3::class, ['toto' => '123', 'Hello' => 'vide']);
        $this->assertArrayHasKey('Hello', $this->hydrator->getNotSetValues());
    }

    public function testWithIntValues(): void
    {
        $result = $this->hydrator->hydrate(\EntitySample3::class, ['toto' => '123', 'myInt' => 5]);
        if ($result instanceof \EntitySample3)
            $this->assertEquals(5, $result->getMyInt());
    }

    public function testWithTranstypeValue(): void
    {
        $result = $this->hydrator->hydrate(\EntitySample3::class, ['toto' => '123', 'myInt' => '5']);
        if ($result instanceof \EntitySample3)
            $this->assertEquals(5, $result->getMyInt());
    }
}

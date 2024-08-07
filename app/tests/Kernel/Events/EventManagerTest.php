<?php

use PHPUnit\Framework\TestCase;
use Editiel98\Kernel\Events\EventManager;

class EventManagerTest extends TestCase
{
    public function testCreate(): void
    {
        $eventManager=EventManager::create();
        $this->assertInstanceOf(EventManager::class, $eventManager);
    }

    public function testNoEvent(): void
    {
        $eventManager=EventManager::create();
        $this->assertFalse($eventManager->hasListener('testEvent'));
    }

    public function testSubscribeEvent(): void
    {
        $eventManager=EventManager::create();
        $eventManager->addListener('testEvent', function(){});
        $this->assertTrue($eventManager->hasListener('testEvent'));
    }

    public function testEmitEvent(): void
    {
        $eventManager=EventManager::create();
        $eventManager->addListener('testEvent1', function(){
            throw new \RuntimeException('');
        });
        $this->expectException(\RuntimeException::class);
        $eventManager->emit('testEvent1');
    }

    public function testEmitWithArg(): void
    {
        $eventManager=EventManager::create();
        $eventManager->addListener('testEvent', function($arg1){
           throw new \Exception($arg1);
        });
        try{
            $eventManager->emit('testEvent', 'Toto');
        } catch(\Exception $e) {
            $this->assertEquals('Toto', $e->getMessage());
        }
    }

    public function testEmitWithArgs(): void
    {
        $eventManager=EventManager::create();
        $eventManager->addListener('testEvent2', function($arg1, $arg2){
           throw new \Exception($arg1 . $arg2);
        });
        try{
            $eventManager->emit('testEvent2', 'Toto', ' isHere');
        } catch(\Exception $e) {
            $this->assertEquals('Toto isHere', $e->getMessage());
        }
        
    }
}
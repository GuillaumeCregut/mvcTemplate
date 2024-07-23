<?php

use PHPUnit\Framework\TestCase;
use Editiel98\Psr\StoppableEventInterface;
use function PHPUnit\Framework\assertNull;
use Editiel98\Kernel\Events\EventException;

use Editiel98\Kernel\Events\ListenerProvider;
use Editiel98\Kernel\Events\EventsKernel\InitKernelEvent;
use Editiel98\Kernel\Events\ListenerInterface;

class ListenerProviderTest extends TestCase
{
    public function testProviderInstance(): void
    {
        $provider= ListenerProvider::getInstance();
        $this->assertInstanceOf(ListenerProvider::class, $provider);
    }

    public function testProviderAddBadEvent(): void
    {
       // $event = $this->createStub(StoppableEventInterface::class);
       $event = $this->createStub(stdClass::class);
       $listener = $this->createStub(ListenerInterface::class);
        $provider=new ListenerProvider();
        $this->expectException(EventException::class);
        $provider->addListener($event::class, $listener );
    }



    public function testPriverAddEvent()
    {
        $event = $this->createStub(StoppableEventInterface::class);
        $listener = $this->createStub(ListenerInterface::class);
        $provider=new ListenerProvider();
        $provider->addListener($event::class, $listener );
        $this->assertArrayHasKey($event::class, $provider->getListeners());
        $this->assertIsArray($provider->getListeners($event::class));
    }

    public function testProviderPriority(): void
    {
        $listener1 = $this->createStub(ListenerInterface::class);
        $listener2 = $this->createMock(ListenerInterface::class);
        $provider=new ListenerProvider();
        $provider->addListener(InitKernelEvent::class, $listener2, 3);
        $provider->addListener(InitKernelEvent::class, $listener1, 5);
        $arrayEvent = $provider->getListenersForEvent(new InitKernelEvent());
        var_dump(get_class($arrayEvent[1]));
        $this->assertEquals([ $listener1,$listener2], $arrayEvent);
    }
}

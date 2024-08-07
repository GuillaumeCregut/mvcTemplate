<?php

use PHPUnit\Framework\TestCase;
use Editiel98\Kernel\Events\EventDispatcher;
use Editiel98\Kernel\Events\ListenerProvider;
use Editiel98\Kernel\Events\ListenerInterface;
use Editiel98\Kernel\Events\EventsKernel\InitKernelEvent;

class EventDispatcherTest extends TestCase
{
    public function testDispatch(): void
    {
        $listener2 = $this->createMock(ListenerInterface::class);
        $provider=new ListenerProvider();
        $provider->addListener(InitKernelEvent::class, $listener2, 3);
        $eventDispatcher= new EventDispatcher($provider);
        $listener2->expects($this->once())
        ->method('execute');
        $eventDispatcher->dispatch(new InitKernelEvent());       
    }
}
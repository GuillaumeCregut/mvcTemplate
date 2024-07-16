<?php

namespace App\Listeners;

use App\Events\DemoEvent;
use Editiel98\Kernel\Events\EventException;

class DemoListener
{
    public function __invoke(DemoEvent $event): void
    {
        if ($event instanceof DemoEvent) {
            //Do stuff here
        } else {
            throw new EventException('Invalid event object');
        }
    }
}

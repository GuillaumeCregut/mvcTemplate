<?php

namespace App\Listeners;

use App\Events\DemoEvent;
use Editiel98\Kernel\Events\EventException;
use Editiel98\Kernel\Events\ListenerInterface;

class DemoListener implements ListenerInterface
{
    public function execute(...$event): void
    {
        if ($event instanceof DemoEvent) {
            //Do stuff here
        } else {
            throw new EventException('Invalid event object');
        }
    }
}

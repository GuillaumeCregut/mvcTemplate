<?php

namespace Editiel98\Psr;

interface ListenerProviderInterface
{
    /**
     * @param object $event
     *
     * @return mixed[]
     */
    public function getListenersForEvent(object $event): iterable;
}

<?php

namespace Discord\WebSockets\Events;

use Discord\WebSockets\Event;
use React\Promise\Deferred;

class ChannelPinsUpdate extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data)
    {
        //wip
    }
}

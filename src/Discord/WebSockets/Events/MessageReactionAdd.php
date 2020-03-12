<?php

namespace Discord\WebSockets\Events;

use Discord\WebSockets\Event;
use React\Promise\Deferred;

class MessageReactionAdd extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data)
    {
        // todo
        $deferred->resolve($data);
    }
}

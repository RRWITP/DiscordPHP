<?php

namespace Discord\WebSockets\Events;

use Discord\WebSockets\Event;
use React\Promise\Deferred;

class MessageReactionRemove extends Event
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

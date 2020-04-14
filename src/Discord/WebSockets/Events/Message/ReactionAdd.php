<?php

namespace Discord\WebSockets\Events\Message;

use Discord\WebSockets\Event;
use React\Promise\Deferred;

/**
 * Class ReactionAdd
 *
 * @package Discord\WebSockets\Events\Message
 */
class ReactionAdd extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data): void
    {
        // todo
        $deferred->resolve($data);
    }
}

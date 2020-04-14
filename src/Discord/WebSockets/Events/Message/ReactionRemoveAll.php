<?php

namespace Discord\WebSockets\Events\Message;

use Discord\WebSockets\Event;
use React\Promise\Deferred;

/**
 * Class ReactionRemoveAll
 *
 * @package Discord\WebSockets\Events\Message
 */
class ReactionRemoveAll extends Event
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

<?php

namespace Discord\WebSockets\Events;

use Discord\Parts\WebSockets\TypingStart as TypingStartPart;
use Discord\WebSockets\Event;
use React\Promise\Deferred;

/**
 * Class TypingStart
 *
 * @package Discord\WebSockets\Events
 */
class TypingStart extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data): void
    {
        $typing = $this->factory->create(TypingStartPart::class, $data, true);

        $deferred->resolve($typing);
    }
}

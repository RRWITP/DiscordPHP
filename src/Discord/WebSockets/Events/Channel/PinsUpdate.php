<?php

namespace Discord\WebSockets\Events\Channel;

use Discord\WebSockets\Event;
use React\Promise\Deferred;

/**
 * Class PinsUpdate
 *
 * @package Discord\WebSockets\Events\Channel
 */
class PinsUpdate extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data): void
    {
        //wip
    }
}

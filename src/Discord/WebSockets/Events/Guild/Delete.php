<?php

namespace Discord\WebSockets\Events;

use Discord\Parts\Guild\Guild;
use Discord\WebSockets\Event;
use React\Promise\Deferred;

class Delete extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data): void
    {
        $guildPart = $this->factory->create(Guild::class, $data, true);

        $this->discord->guilds->pull($guildPart->id);

        $deferred->resolve($guildPart);
    }
}

<?php

namespace Discord\WebSockets\Events\Channel;

use Discord\Parts\Channel\Channel;
use Discord\WebSockets\Event;
use React\Promise\Deferred;

/**
 * Class Delete
 *
 * @package Discord\WebSockets\Events\Channel
 */
class Delete extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data): void
    {
        $channel = $this->factory->create(Channel::class, $data);

        $guild = $this->discord->guilds->get('id', $channel->guild_id);
        $guild->channels->pull($channel->id);

        $deferred->resolve($channel);
    }
}

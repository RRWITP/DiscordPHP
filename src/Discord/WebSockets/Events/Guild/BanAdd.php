<?php

namespace Discord\WebSockets\Events;

use Discord\Parts\Guild\Ban;
use Discord\WebSockets\Event;
use React\Promise\Deferred;

class BanAdd extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data): void
    {
        $guild = $this->discord->guilds->get('id', $data->guild_id);
        $ban   = $this->factory->create(Ban::class, [
            'guild' => $guild,
            'user'  => $data->user,
        ], true);

        $guild = $this->discord->guilds->get('id', $ban->guild->id);
        $guild->bans->push($ban);

        $deferred->resolve($ban);
    }
}

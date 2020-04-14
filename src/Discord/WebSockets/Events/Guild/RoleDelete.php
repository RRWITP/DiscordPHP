<?php

namespace Discord\WebSockets\Events\Guild;

use React\Promise\Deferred;
use Discord\WebSockets\Event;

/**
 * Class RoleDelete
 *
 * @package Discord\WebSockets\Events\Guild
 */
class RoleDelete extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data): void
    {
        $guild = $this->discord->guilds->get('id', $data->guild_id);
        $guild->roles->pull($data->role_id);

        $deferred->resolve($data);
    }
}

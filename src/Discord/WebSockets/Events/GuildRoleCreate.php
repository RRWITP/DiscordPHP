<?php

namespace Discord\WebSockets\Events;

use Discord\Parts\Guild\Role;
use Discord\WebSockets\Event;
use React\Promise\Deferred;

class GuildRoleCreate extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data)
    {
        $adata             = (array) $data->role;
        $adata['guild_id'] = $data->guild_id;

        $rolePart = $this->factory->create(Role::class, $adata, true);

        $guild = $this->discord->guilds->get('id', $rolePart->guild_id);
        if (! is_null($guild)) {
            $guild->roles->push($rolePart);
        }

        $deferred->resolve($rolePart);
    }
}

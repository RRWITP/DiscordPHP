<?php

namespace Discord\WebSockets\Events\Guild;

use Discord\Parts\Guild\Role;
use Discord\WebSockets\Event;
use React\Promise\Deferred;

/**
 * Class RoleCreate
 *
 * @package Discord\WebSockets\Events\Guild
 */
class RoleCreate extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data): void
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

<?php

namespace Discord\WebSockets\Events\Guild;

use Discord\Parts\Guild\Role;
use Discord\WebSockets\Event;
use React\Promise\Deferred;

/**
 * Class RoleUpdate
 *
 * @package Discord\WebSockets\Events\Guild
 */
class RoleUpdate extends Event
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
        $old   = $guild->roles->get('id', $rolePart->id);
        $guild->roles->push($rolePart);

        $deferred->resolve([$rolePart, $old]);
    }
}

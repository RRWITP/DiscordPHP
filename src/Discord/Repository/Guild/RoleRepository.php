<?php

namespace Discord\Repository\Guild;

use Discord\Parts\Guild\Role;
use Discord\Repository\AbstractRepository;

/**
 * Contains roles that belong to the guild.
 *
 * @see Discord\Parts\Guild\Role
 * @see Discord\Parts\Guild\Guild
 */
class RoleRepository extends AbstractRepository
{
    /**
     * {@inheritdoc}
     */
    protected $endpoints = [
        'all'    => 'guilds/:guild_id/roles',
        'get'    => 'guilds/:guild_id/roles/:id',
        'create' => 'guilds/:guild_id/roles',
        'update' => 'guilds/:guild_id/roles/:id',
        'delete' => 'guilds/:guild_id/roles/:id',
    ];

    /**
     * {@inheritdoc}
     */
    protected $class = Role::class;
}

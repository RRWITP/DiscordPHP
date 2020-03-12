<?php

namespace Discord\Repository\Guild;

use Discord\Parts\Guild\Invite;
use Discord\Repository\AbstractRepository;

/**
 * Contains invites to guilds.
 *
 * @see Discord\Parts\Guild\Invite
 * @see Discord\Parts\Guild\Guild
 */
class InviteRepository extends AbstractRepository
{
    /**
     * {@inheritdoc}
     */
    protected $endpoints = [
        'all'    => 'guilds/:guild_id/invites',
        'get'    => 'invites/:code',
        'create' => 'guilds/:guild_id/invites',
        'delete' => 'invites/:code',
    ];

    /**
     * {@inheritdoc}
     */
    protected $part = Invite::class;
}

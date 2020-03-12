<?php

namespace Discord\Repository\Guild;

use Discord\Parts\Channel\Channel;
use Discord\Repository\AbstractRepository;

/**
 * Contains channels that belong to guilds.
 *
 * @see Discord\Parts\Channel\Channel
 * @see Discord\Parts\Guild\Guild
 */
class ChannelRepository extends AbstractRepository
{
    /**
     * {@inheritdoc}
     */
    protected $endpoints = [
        'all'    => 'guilds/:guild_id/channels',
        'get'    => 'channels/:id',
        'create' => 'guilds/:guild_id/channels',
        'update' => 'channels/:id',
        'delete' => 'channels/:id',
    ];

    /**
     * {@inheritdoc}
     */
    protected $part = Channel::class;
}

<?php

namespace Discord\Repository\Channel;

use Discord\Parts\WebSockets\VoiceStateUpdate;
use Discord\Repository\AbstractRepository;

/**
 * Contains voice states for users in the voice channel.
 *
 * @see Discord\Parts\WebSockets\VoiceStateUpdate
 * @see Discord\Parts\Channel\Channel
 */
class VoiceMemberRepository extends AbstractRepository
{
    /**
     * {@inheritdoc}
     */
    protected $endpoints = [];

    /**
     * {@inheritdoc}
     */
    protected $class = VoiceStateUpdate::class;
}

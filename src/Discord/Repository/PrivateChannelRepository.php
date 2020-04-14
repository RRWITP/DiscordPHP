<?php

namespace Discord\Repository;

use Discord\Parts\Channel\Channel;

/**
 * Contains private channels and groups that the user has access to.
 *
 * @see Discord\Parts\Channel\Channel
 */
class PrivateChannelRepository extends AbstractRepository
{
    /**
     * {@inheritdoc}
     */
    protected $endpoints = [];

    /**
     * {@inheritdoc}
     */
    protected $part = Channel::class;
}

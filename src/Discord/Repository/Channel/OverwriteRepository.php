<?php

namespace Discord\Repository\Channel;

use Discord\Parts\Channel\Overwrite;
use Discord\Repository\AbstractRepository;

/**
 * Contains permission overwrites for channels.
 *
 * @see Discord\Parts\Channel\Overwrite
 * @see Discord\Parts\Channel\Channel
 */
class OverwriteRepository extends AbstractRepository
{
    /**
     * {@inheritdoc}
     */
    protected $endpoints = [
        'delete' => 'channels/:channel_id/permissions/:id',
    ];

    /**
     * {@inheritdoc}
     */
    protected $part = Overwrite::class;
}

<?php

namespace Discord\Repository;

use Discord\Parts\User\User;

/**
 * Contains users that the user shares guilds with.
 *
 * @see Discord\Parts\User\User
 */
class UserRepository extends AbstractRepository
{
    /**
     * {@inheritdoc}
     */
    protected $endpoints = [
        'get' => 'users/:id',
    ];

    /**
     * {@inheritdoc}
     */
    protected $part = User::class;
}

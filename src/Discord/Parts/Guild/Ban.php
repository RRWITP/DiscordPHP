<?php

namespace Discord\Parts\Guild;

use Discord\Parts\Part;
use Discord\Parts\User\User;

/**
 * A Ban is a ban on a user specific to a guild. It is also IP based.
 *
 * @property \Discord\Parts\User\User   $user   The user that was banned.
 * @property \Discord\Parts\Guild\Guild $guild  The guild that the user was banned from.
 * @property string|null                $reason The reason the user was banned.
 */
class Ban extends Part
{
    /**
     * {@inheritdoc}
     */
    protected $fillable = ['user', 'guild', 'reason'];

    /**
     * Returns the guild id attribute.
     *
     * @return int
     */
    public function getGuildIdAttribute(): int
    {
        return $this->guild->id;
    }

    /**
     * Returns the user id attribute.
     *
     * @return int
     */
    public function getUserIdAttribute(): int
    {
        return $this->user->id;
    }

    /**
     * Gets the user attribute.
     *
     * @return User
     *
     * @throws \Exception
     */
    public function getUserAttribute(): User
    {
        return $this->factory->create(User::class, (array) $this->attributes['user']);
    }

    /**
     * Gets the guild attribute.
     *
     * @return Guild
     */
    public function getGuildAttribute(): Guild
    {
        return $this->discord->guilds->get('id', $this->attributes['guild']->id);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatableAttributes(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatableAttributes(): array
    {
        return [];
    }
}

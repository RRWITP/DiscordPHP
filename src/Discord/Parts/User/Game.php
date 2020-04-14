<?php

namespace Discord\Parts\User;

use Discord\Parts\Part;

/**
 * The Game part defines what game the user is playing at the moment.
 *
 * @property string $name The name of the game.
 * @property string $url  The URL to the stream if the user is streaming.
 * @property int    $type The type of game, either playing or streaming.
 */
class Game extends Part
{
    public const TYPE_PLAYING   = 0;
    public const TYPE_STREAMING = 1;

    /**
     * {@inheritdoc}
     */
    protected $fillable = ['name', 'url', 'type'];
}

<?php

namespace Discord\WebSockets;

use Discord\Discord;
use Discord\Factory\Factory;
use Discord\Http\Http;
use Discord\Wrapper\CacheWrapper;
use Evenement\EventEmitterTrait;
use React\Promise\Deferred;

/**
 * Class Event
 *
 * Contains constants for WebSocket events as well as handlers
 * for the events.
 *
 * @package Discord\Websockets
 */
abstract class Event
{
    use EventEmitterTrait;

    // General
    public const READY                = 'READY';
    public const RESUMED              = 'RESUMED';
    public const PRESENCE_UPDATE      = 'PRESENCE_UPDATE';
    public const PRESENCES_REPLACE    = 'PRESENCES_REPLACE';
    public const TYPING_START         = 'TYPING_START';
    public const USER_SETTINGS_UPDATE = 'USER_SETTINGS_UPDATE';
    public const VOICE_STATE_UPDATE   = 'VOICE_STATE_UPDATE';
    public const VOICE_SERVER_UPDATE  = 'VOICE_SERVER_UPDATE';
    public const GUILD_MEMBERS_CHUNK  = 'GUILD_MEMBERS_CHUNK';

    // Guild
    public const GUILD_CREATE         = 'GUILD_CREATE';
    public const GUILD_DELETE         = 'GUILD_DELETE';
    public const GUILD_UPDATE         = 'GUILD_UPDATE';

    public const GUILD_BAN_ADD        = 'GUILD_BAN_ADD';
    public const GUILD_BAN_REMOVE     = 'GUILD_BAN_REMOVE';
    public const GUILD_MEMBER_ADD     = 'GUILD_MEMBER_ADD';
    public const GUILD_MEMBER_REMOVE  = 'GUILD_MEMBER_REMOVE';
    public const GUILD_MEMBER_UPDATE  = 'GUILD_MEMBER_UPDATE';
    public const GUILD_ROLE_CREATE    = 'GUILD_ROLE_CREATE';
    public const GUILD_ROLE_UPDATE    = 'GUILD_ROLE_UPDATE';
    public const GUILD_ROLE_DELETE    = 'GUILD_ROLE_DELETE';

    // Channel
    public const CHANNEL_CREATE      = 'CHANNEL_CREATE';
    public const CHANNEL_DELETE      = 'CHANNEL_DELETE';
    public const CHANNEL_UPDATE      = 'CHANNEL_UPDATE';
    public const CHANNEL_PINS_UPDATE = 'CHANNEL_PINS_UPDATE';

    // Messages
    public const MESSAGE_CREATE              = 'MESSAGE_CREATE';
    public const MESSAGE_DELETE              = 'MESSAGE_DELETE';
    public const MESSAGE_UPDATE              = 'MESSAGE_UPDATE';
    public const MESSAGE_DELETE_BULK         = 'MESSAGE_DELETE_BULK';
    public const MESSAGE_REACTION_ADD        = 'MESSAGE_REACTION_ADD';
    public const MESSAGE_REACTION_REMOVE     = 'MESSAGE_REACTION_REMOVE';
    public const MESSAGE_REACTION_REMOVE_ALL = 'MESSAGE_REACTION_REMOVE_ALL';

    /**
     * The HTTP client.
     */
    protected Http $http;

    /**
     * The Factory.
     */
    protected Factory $factory;

    /**
     * The cacheWrapper for interaction with the cache.
     */
    protected CacheWrapper $cache;

    /**
     * The Discord client instance.
     */
    protected Discord $discord;

    /**
     * Constructs an event.
     *
     * @param Http         $http    The HTTP client.
     * @param Factory      $factory The factory.
     * @param CacheWrapper $cache   The cache.
     * @param Discord      $discord The Discord client.
     *
     * @return void
     */
    public function __construct(Http $http, Factory $factory, CacheWrapper $cache, Discord $discord)
    {
        $this->http    = $http;
        $this->factory = $factory;
        $this->cache   = $cache;
        $this->discord = $discord;
    }

    /**
     * Transforms the given data, and updates the Discord instance if necessary.
     *
     * @param  Deferred $deferred The promise to use
     * @param  array    $data     The data that was sent with the WebSocket
     * @return void
     */
    abstract public function handle(Deferred $deferred, $data): void;
}

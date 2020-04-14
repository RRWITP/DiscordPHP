<?php

namespace Discord\WebSockets;

/**
 * Contains constants used in websockets.
 *
 * @package Discord\WebSockets
 */
class Op
{
    public const OP_DISPATCH           = 0;      // Dispatches an event.
    public const OP_HEARTBEAT          = 1;      // Used for ping checking.
    public const OP_IDENTIFY           = 2;      // Used for client handshake.
    public const OP_PRESENCE_UPDATE    = 3;      // Used to update the client presence.
    public const OP_VOICE_STATE_UPDATE = 4;      // Used to join/move/leave voice channels.
    public const OP_VOICE_SERVER_PING  = 5;      // Used for voice ping checking.
    public const OP_RESUME             = 6;      // Used to resume a closed connection.
    public const OP_RECONNECT          = 7;      // Used to redirect clients to a new gateway.
    public const OP_GUILD_MEMBER_CHUNK = 8;      // Used to request member chunks.
    public const OP_INVALID_SESSION    = 9;      // Used to notify clients when they have an invalid session.
    public const OP_HELLO              = 10;     // Used to pass through the heartbeat interval
    public const OP_HEARTBEAT_ACK      = 11;     // Used to acknowledge heartbeats.

    ///////////////////////////////////////
    ///////////////////////////////////////
    ///////////////////////////////////////

    public const VOICE_IDENTIFY        = 0;      // Used to begin a voice WebSocket connection.
    public const VOICE_SELECT_PROTO    = 1;      // Used to select the voice protocol.
    public const VOICE_READY           = 2;      // Used to complete the WebSocket handshake.
    public const VOICE_HEARTBEAT       = 3;      // Used to keep the WebSocket connection alive.
    public const VOICE_DESCRIPTION     = 4;      // Used to describe the session.
    public const VOICE_SPEAKING        = 5;      // Used to identify which users are speaking.

    ///////////////////////////////////////
    ///////////////////////////////////////
    ///////////////////////////////////////

    public const CLOSE_NORMAL            = 1000; // Normal close or heartbeat is invalid.
    public const CLOSE_ABNORMAL          = 1006; // Abnormal close.
    public const CLOSE_UNKNOWN_ERROR     = 1000; // Unknown error.
    public const CLOSE_INVALID_OPCODE    = 4001; // Unknown opcode was went.
    public const CLOSE_INVALID_MESSAGE   = 4002; // Invalid message was sent.
    public const CLOSE_NOT_AUTHENTICATED = 4003; // Not authenticated.
    public const CLOSE_INVALID_TOKEN     = 4004; // Invalid token on IDENTIFY.
    public const CONST_ALREADY_AUTHD     = 4005; // Already authenticated.
    public const CLOSE_INVALID_SESSION   = 4006; // Session is invalid.
    public const CLOSE_INVALID_SEQ       = 4007; // Invalid RESUME sequence.
    public const CLOSE_TOO_MANY_MSG      = 4008; // Too many messages sent.
    public const CLOSE_SESSION_TIMEOUT   = 4009; // Session timeout.
    public const CLOSE_INVALID_SHARD     = 4010; // Invalid shard.
    public const CLOSE_SHARDING_REQUIRED = 4011; // Sharding requred.
}

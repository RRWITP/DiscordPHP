<?php

namespace Discord\Parts\Permissions;

/**
 * {@inheritdoc}
 *
 * @property bool $create_instant_invite    Allows creation of instant invites
 * @property bool $kick_members             Allowing kicking members
 * @property bool $ban_members              Allows banning members
 * @property bool $administrator            Allows all permissions and bypasses channel permission overwrites.
 * @property bool $manage_channels          Allows management and editing of channels.
 * @property bool $manage_server            Allows for managing the discord server.
 * @property bool $change_nickname          Allows modification of own nickname
 * @property bool $manage_nicknames         Allows for modification of other users nicknames
 * @property bool $manage_roles             Allows management and editing of roles
 * @property bool $read_messages            Allows for reading messages in a channel
 * @property bool $send_messages            Allows for sending messages in a channel
 * @property bool $send_tts_messages        Allows for sending of /tss messages.
 * @property bool $manage_messages          Allows for deletion of other messages
 * @property bool $embed_links              Links sent by users with this permission will be auto-embedded
 * @property bool $attach_files             Allows for uploading images and files
 * @property bool $read_message_history     Allows for reading the message history
 * @property bool $mention_everyone         Allows for using the @everyone tag to notify all the users in a channel. or @here for all the online users
 * @property bool $voice_connect            Allows for joining a voice channel
 * @property bool $voice_speak              Allows for speaking a voice channel
 * @property bool $voice_mute_members       Allows for muting members in a voice channel
 * @property bool $voice_deafen_members     Allows for deafening of members in a voice channel
 * @property bool $voice_move_members       Allows for moving of members between voice channels
 * @property bool $voice_use_vad            Allows for using voice-activity-detection in a voice channel
 *
 * @see https://discordapp.com/developers/docs/topics/permissions
 */
class RolePermission extends Permission
{
    /**
     * {@inheritdoc}
     */
    protected $bitwise = [
        'create_instant_invite' => 0,
        'kick_members'          => 1,
        'ban_members'           => 2,
        'administrator'         => 3,
        'manage_channels'       => 4,
        'manage_server'         => 5,
        'change_nickname'       => 26,
        'manage_nicknames'      => 27,
        'manage_roles'          => 28,

        'read_messages'        => 10,
        'send_messages'        => 11,
        'send_tts_messages'    => 12,
        'manage_messages'      => 13,
        'embed_links'          => 14,
        'attach_files'         => 15,
        'read_message_history' => 17,
        'mention_everyone'     => 18,

        'voice_connect'        => 20,
        'voice_speak'          => 21,
        'voice_mute_members'   => 22,
        'voice_deafen_members' => 23,
        'voice_move_members'   => 24,
        'voice_use_vad'        => 25,
    ];

    /**
     * {@inheritdoc}
     */
    public function getDefault(): array
    {
        return [
            'create_instant_invite' => true,

            'read_messages'        => true,
            'send_messages'        => true,
            'send_tts_messages'    => true,
            'embed_links'          => true,
            'attach_files'         => true,
            'read_message_history' => true,
            'mention_everyone'     => true,

            'voice_connect' => true,
            'voice_speak'   => true,
            'voice_use_vad' => true,
        ];
    }
}

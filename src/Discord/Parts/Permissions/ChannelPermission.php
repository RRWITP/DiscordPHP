<?php

namespace Discord\Parts\Permissions;

/**
 * {@inheritdoc}
 *
 * @property bool $create_instant_invite    Allows creation of instant invites
 * @property bool $manage_channels          Allows management ond editing of channels.
 * @property bool $manage_permissions       Allows management and editing the permissions
 * @property bool $read_messages            Allows for reading messages in a channel.
 * @property bool $send_messages            Allows for sending messages in a channel
 * @property bool $send_tts_messages        Allows for sending of /tss messages
 * @property bool $manage_messages          Allows for deletion of other users messages
 * @property bool $embed_links              Links sent by users with this permission will be auto-embedded
 * @property bool $attach_files             Allows for uploading images and files
 * @property bool $read_message_history     Allows for reading of message history
 * @property bool $mention_everyone         Allows for using the @everyone tag to notify all users in a channel, and @here for all the online users
 * @property bool $voice_connect            Allows for joining of a voice channel
 * @property bool $voice_speak              Allows for speaking in a voice channel
 * @property bool $voice_mute_members       Allows for muting members in a voice channel
 * @property bool $voice_deafen_members     Allows for deafening of members in a voice channel
 * @property bool $voice_move_members       Allows for moving of members between voice channels
 * @property bool $voice_use_vad            Allows for using voice-activity-detection in a voice channel
 *
 * @see https://discordapp.com/developers/docs/topics/permissions
 */
class ChannelPermission extends Permission
{
    /**
     * {@inheritdoc}
     */
    protected $bitwise = [
        'create_instant_invite' => 0,
        'manage_channels'       => 4,
        'manage_permissions'    => 28,

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
     *
     * @param  int $deny The deny bitwise integer.
     * @return self
     */
    public function decodeBitwise($bitwise, $deny = 0): self
    {
        $result = $this->getDefault();

        foreach ($this->bitwise as $key => $value) {
            if (true === ((($bitwise >> $value) & 1) == 1)) {
                $result[$key] = true;
            } elseif (true === ((($deny >> $value) & 1) == 1)) {
                $result[$key] = false;
            }
        }

        $this->fill($result);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return array Bitwise.
     */
    public function getBitwiseAttribute(): array
    {
        $allow = 0;
        $deny  = 0;

        foreach ($this->attributes as $key => $value) {
            if (true === $value) {
                $allow |= (1 << $this->bitwise[$key]);
            } elseif (false === $value) {
                $deny |= (1 << $this->bitwise[$key]);
            }
        }

        return [$allow, $deny];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefault(): array
    {
        $default = [];

        foreach ($this->bitwise as $key => $bit) {
            $default[$key] = null;
        }

        return $default;
    }
}

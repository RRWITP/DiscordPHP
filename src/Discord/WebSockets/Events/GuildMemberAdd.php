<?php

namespace Discord\WebSockets\Events;

use Discord\Parts\User\Member;
use Discord\WebSockets\Event;
use React\Promise\Deferred;

class GuildMemberAdd extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data)
    {
        $memberPart = $this->factory->create(Member::class, $data, true);

        $guild = $this->discord->guilds->get('id', $memberPart->guild_id);

        if (! is_null($guild)) {
            $guild->members->push($memberPart);
            ++$guild->member_count;

            $this->discord->guilds->push($guild);
        }

        $deferred->resolve($memberPart);
    }
}

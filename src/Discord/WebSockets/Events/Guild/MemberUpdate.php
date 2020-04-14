<?php

namespace Discord\WebSockets\Events;

use Discord\Parts\User\Member;
use Discord\WebSockets\Event;
use React\Promise\Deferred;

class MemberUpdate extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data): void
    {
        $memberPart = $this->factory->create(Member::class, $data, true);
        $old        = null;

        $guild = $this->discord->guilds->get('id', $memberPart->guild_id);

        if (! is_null($guild)) {
            $old        = $guild->members->get('id', $memberPart->id);
            $raw        = (is_null($old)) ? [] : $old->getRawAttributes();
            $memberPart = $this->factory->create(Member::class, array_merge($raw, (array) $data), true);

            $guild->members->push($memberPart);

            $this->discord->guilds->push($guild);
        }

        $deferred->resolve([$memberPart, $old]);
    }
}

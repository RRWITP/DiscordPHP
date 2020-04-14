<?php

namespace Discord\WebSockets\Events\Guild;

use Discord\Parts\User\Member;
use Discord\WebSockets\Event;
use React\Promise\Deferred;

/**
 * Class MemberAdd
 *
 * @package Discord\WebSockets\Events\Guild
 */
class MemberAdd extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data): void
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

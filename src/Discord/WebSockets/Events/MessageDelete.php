<?php

namespace Discord\WebSockets\Events;

use Discord\Repository\Channel\MessageRepository;
use Discord\WebSockets\Event;
use React\Promise\Deferred;

class MessageDelete extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data)
    {
        $messages = $this->discord->getRepository(
            MessageRepository::class,
            $data->channel_id,
            'messages',
            ['channel_id' => $data->channel_id]
        );
        $messages->pull($data->id);

        $deferred->resolve($data->id);
    }
}

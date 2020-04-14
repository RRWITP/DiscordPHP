<?php

namespace Discord\WebSockets\Events;

use Discord\Repository\Channel\MessageRepository;
use Discord\WebSockets\Event;
use React\Promise\Deferred;

class DeleteBulk extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data): void
    {
        $messages = $this->discord->getRepository(
            MessageRepository::class,
            $data->channel_id,
            'messages',
            ['channel_id' => $data->channel_id]
        );

        foreach ($data->ids as $message) {
            $messages->pull($message);
        }

        $deferred->resolve($data);
    }
}

<?php

namespace Discord\WebSockets\Events;

use Discord\Parts\Channel\Message;
use Discord\Repository\Channel\MessageRepository;
use Discord\WebSockets\Event;
use React\Promise\Deferred;

class Create extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data): void
    {
        $messagePart = $this->factory->create(Message::class, $data, true);

        if ($this->discord->options['storeMessages']) {
            $messages = $this->discord->getRepository(
                MessageRepository::class,
                $messagePart->channel_id,
                'messages',
                ['channel_id' => $messagePart->channel_id]
            );
            $messages->push($messagePart);
        }

        $deferred->resolve($messagePart);
    }
}

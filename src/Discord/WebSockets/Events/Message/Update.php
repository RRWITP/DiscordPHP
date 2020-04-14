<?php

namespace Discord\WebSockets\Events;

use Discord\Parts\Channel\Message;
use Discord\Repository\Channel\MessageRepository;
use Discord\WebSockets\Event;
use React\Promise\Deferred;

class Update extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred $deferred, $data): void
    {
        $messagePart = $this->factory->create(Message::class, $data, true);

        $messages = $this->discord->getRepository(
            MessageRepository::class,
            $messagePart->channel_id,
            'messages',
            ['channel_id' => $messagePart->channel_id]
        );
        $message = $messages->get('id', $messagePart->id);

        if (is_null($message)) {
            $newMessage = $messagePart;
        } else {
            $newMessage = $this->factory->create(Message::class, array_merge($message->getRawAttributes(), $messagePart->getRawAttributes()), true);
        }

        $old = $messages->get('id', $messagePart->id);
        $messages->push($newMessage);

        $deferred->resolve([$messagePart, $old]);
    }
}

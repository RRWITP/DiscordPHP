<?php

namespace Discord\WebSockets\Events\Message;

use Discord\Repository\Channel\MessageRepository;
use Discord\WebSockets\Event;
use React\Promise\Deferred;

/**
 * Class Delete
 *
 * @package Discord\WebSockets\Events\Message
 */
class Delete extends Event
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
        $messages->pull($data->id);

        $deferred->resolve($data->id);
    }
}

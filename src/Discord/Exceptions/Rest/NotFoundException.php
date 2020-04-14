<?php

namespace Discord\Exceptions\Rest;

use Discord\Exceptions\DiscordRequestFailedException;

/**
 * Thrown when a 404 Not Found response is received.
 */
class NotFoundException extends DiscordRequestFailedException
{
}

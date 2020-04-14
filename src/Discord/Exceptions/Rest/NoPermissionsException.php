<?php

namespace Discord\Exceptions\Rest;

use Discord\Exceptions\DiscordRequestFailedException;

/**
 * Thrown when you do not have permissions to do something.
 */
class NoPermissionsException extends DiscordRequestFailedException
{
}

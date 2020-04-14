<?php

namespace Discord\Exceptions\Rest;

use Discord\Exceptions\DiscordRequestFailedException;

/**
 * Class ContentTooLongException
 *
 * Thrown when the Discord servers return `content longer than 2000 characters` after
 * a REST request. The user must use WebSockets to obtain this data if they need it.
 *
 * @package Discord\Exceptions\Rest
 */
class ContentTooLongException extends DiscordRequestFailedException
{
    //
}

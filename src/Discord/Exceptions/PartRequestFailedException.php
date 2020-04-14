<?php

namespace Discord\Exceptions;

/**
 * Class PartRequestFailedException
 *
 * Thrown when a request that was executed from a part failed.
 *
 * @see \Discord\Parts\Part::save() Can be thrown when being saved.
 * @see \Discord\Parts\Part::delete() Can be thrown when being deleted.
 *
 * @package Discord\Exceptions
 */
class PartRequestFailedException extends \Exception
{
    //
}

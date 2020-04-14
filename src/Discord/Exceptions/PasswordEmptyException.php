<?php

namespace Discord\Exceptions;

/**
 * Class PasswordEmptyException
 *
 * Thrown when the user tries to update the Discord user without providing
 * the account password.
 *
 * @see \Discord\Parts\User\Client
 *
 * @package Discord\Exceptions
 */
class PasswordEmptyException extends \Exception
{
    //
}

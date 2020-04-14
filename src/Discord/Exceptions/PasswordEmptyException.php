<?php

namespace Discord\Exceptions;

/**
 * Thrown when the user tries to update the Discord user without providing
 * the account password.
 *
 * @see \Discord\Parts\User\Client
 */
class PasswordEmptyException extends \Exception
{
}

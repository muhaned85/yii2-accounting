<?php

namespace muh\accounting\Exceptions;

use Exception;

/**
 * Class EntryNotFoundException
 *
 * This exception is thrown when a requested journal entry is not found in the system.
 *
 * @package muh\accounting\Exceptions
 */
class EntryNotFoundException extends Exception
{
    /**
     * EntryNotFoundException constructor.
     *
     * @param string $message Exception message, defaults to a standard not found message.
     * @param int $code Exception code, defaults to 0.
     * @param Exception|null $previous Previous exception, if any.
     */
    public function __construct($message = "The journal entry was not found.", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

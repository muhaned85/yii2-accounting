<?php

namespace muh\accounting\Exceptions;

use Exception;

/**
 * Class UnbalancedEntryException
 *
 * This exception is thrown when a journal entry's total debits do not equal its total credits,
 * indicating that the entry is unbalanced.
 *
 * @package muh\accounting\Exceptions
 */
class UnbalancedEntryException extends Exception
{
    /**
     * UnbalancedEntryException constructor.
     *
     * @param string $message Exception message, defaults to a standard unbalanced entry message.
     * @param int $code Exception code, defaults to 0.
     * @param Exception|null $previous Previous exception, if any.
     */
    public function __construct($message = "The journal entry is unbalanced. Total debits do not equal total credits.", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

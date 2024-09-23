<?php
declare(strict_types=1);

namespace CleanCode\Cleansing;

use Exception;

class NumberFormatException extends Exception
{
    public function __construct($message = "Invalid number format", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
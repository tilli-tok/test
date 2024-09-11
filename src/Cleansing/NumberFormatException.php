<?php
namespace CleanCode\Cleansing;

use Exception;

class NumberFormatException extends Exception
{
    public function __construct(string $errorCode)
    {
        parent::__construct($errorCode);
    }
}
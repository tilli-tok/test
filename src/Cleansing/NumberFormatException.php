<?php

namespace CleanCode\Cleansing;

class NumberFormatException extends \Exception
{
    public function __construct(string $errorCode)
    {
        parent::__construct($errorCode);
    }
}
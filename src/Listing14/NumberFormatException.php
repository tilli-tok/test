<?php
namespace CleanCode\Listing14;

class NumberFormatException extends \Exception
{
    public function __construct(string $errorCode)
    {
        parent::__construct($errorCode);
    }
}
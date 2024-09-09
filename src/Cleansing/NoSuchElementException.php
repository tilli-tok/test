<?php

namespace CleanCode\Cleansing;

class NoSuchElementException extends \Exception
{
    public function __construct(string $errorCode, $parameter = null)
    {
        parent::__construct($errorCode, $parameter);
    }
}
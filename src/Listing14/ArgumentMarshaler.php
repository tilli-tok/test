<?php
declare(strict_types=1);
namespace CleanCode\Listing14;

class ArgumentMarshaler
{
    private bool $booleanValue = false;

    public function setBoolean(bool $value): void
    {
        $this->booleanValue = $value;
    }

    public function getBoolean(): bool
    {
        return $this->booleanValue;
    }
}
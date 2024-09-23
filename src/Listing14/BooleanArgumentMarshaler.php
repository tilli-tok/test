<?php
declare(strict_types=1);

namespace CleanCode\Listing14;

use Iterator;

class BooleanArgumentMarshaler implements ArgumentMarshaler
{
    private bool $booleanValue = false;

    /**
     * {@inheritDoc}
     */
    public function set(Iterator $currentArgument): void
    {
        $this->booleanValue = true;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): bool
    {
        return $this->booleanValue;
    }
}

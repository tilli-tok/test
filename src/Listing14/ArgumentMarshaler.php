<?php
declare(strict_types=1);
namespace CleanCode\Listing14;

use Iterator;

interface ArgumentMarshaler
{
    /**
     * @param Iterator $currentArgument
     * @return void
     */
    public function set(Iterator $currentArgument): void;

    public function get(): mixed;
}
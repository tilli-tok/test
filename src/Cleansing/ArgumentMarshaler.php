<?php
declare(strict_types=1);
namespace CleanCode\Cleansing;

use Iterator;

interface ArgumentMarshaler{

    /**
     * @param Iterator $currentArgument
     * @return void
     * @throws ArgsException
     */
    public function set(Iterator $currentArgument): void;
}
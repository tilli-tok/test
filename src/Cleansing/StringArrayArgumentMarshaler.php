<?php
declare(strict_types=1);

namespace CleanCode\Cleansing;

use Iterator;

class StringArrayArgumentMarshaler implements ArgumentMarshaler
{
    /**
     * @var string[] $stringArrayValue
     */
    private array $stringArrayValue = [];

    public function set(Iterator $currentArgument): void
    {

    }

    /**
     * @return string[]
     */
    public static function getValue(?ArgumentMarshaler $am): array
    {
        if ($am instanceof StringArrayArgumentMarshaler) {
            return $am->stringArrayValue;
        } else {
            return [];
        }
    }
}

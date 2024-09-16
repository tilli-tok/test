<?php
declare(strict_types=1);

namespace CleanCode\Classes;

/**
 * @template T
 * @implements \IteratorAggregate<T>
 */
abstract class ArrayList
{
    /**
     * @var T[]
     */
    private array $elements = [];

    /**
     * @param T $element
     * @return void
     */
    public function add($element): void
    {
        $this->elements[] = $element;
    }

    public function size(): int
    {
        return count($this->elements);
    }
}

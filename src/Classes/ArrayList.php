<?php
declare(strict_types=1);

namespace CleanCode\Classes;

use ArrayIterator;
use Countable;
use Traversable;
use IteratorAggregate;

/**
 * @template T
 * @implements IteratorAggregate<T>
 */
class ArrayList implements IteratorAggregate, Countable
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

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->elements);
    }

    public function count(): int
    {
        return count($this->elements);
    }
}
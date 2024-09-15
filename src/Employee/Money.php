<?php
declare(strict_types=1);

namespace CleanCode\Employee;

class Money {
    public Money $type;
    private float $amount;

    public function __construct(float $amount) {
        $this->amount = $amount;
    }
    public function getAmount(): float
    {
        return $this->amount;
    }
}
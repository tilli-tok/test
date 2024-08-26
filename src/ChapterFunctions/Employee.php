<?php
declare(strict_types=1);

namespace CleanCode\ChapterFunctions;

abstract class Employee {
    public abstract function isPayday(): bool;
    public abstract function calculatePay(): Money;
    public abstract function deliverPay(Money $pay): void;
}
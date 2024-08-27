<?php
declare(strict_types=1);

namespace CleanCode\Employee;

class SalariedEmployee extends Employee {
    public function isPayday(): bool {
        return true;
    }

    public function calculatePay(): Money {
        return new Money();
    }

    public function deliverPay(Money $pay): void {
    }
}
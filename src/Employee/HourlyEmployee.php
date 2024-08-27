<?php
declare(strict_types=1);

namespace CleanCode\Employee;

class HourlyEmployee extends Employee {
    public function __construct(EmployeeRecord $r)
    {

    }
    public function isPayday(): bool {
        return true;
    }

    public function calculatePay(): Money {
        return new Money();
    }

    public function deliverPay(Money $pay): void {
    }
}
<?php
declare(strict_types=1);

namespace CleanCode\Employee;

class HourlyEmployee extends Employee
{
    public function __construct(EmployeeRecord $r)
    {
    }

    public function isPayday(): bool
    {
        return true;
    }

    /**
     * @return Money
     */
    public function calculatePay(): Money
    {
        return new Money(300);
    }

    public function deliverPay(Money $pay): void
    {
        echo 'Result Hourly ' . $pay->getAmount() . "\n";
    }
}
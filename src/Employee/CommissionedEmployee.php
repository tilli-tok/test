<?php
declare(strict_types=1);

namespace CleanCode\Employee;

class CommissionedEmployee extends Employee
{
    public function __construct(EmployeeRecord $r)
    {
    }

    public function isPayday(): bool
    {
        return true;
    }

    public function calculatePay(): Money
    {
        return new Money(200);
    }

    public function deliverPay(Money $pay): void
    {
        echo 'Result Commissioned ' . $pay->getAmount() . "\n";
    }
}
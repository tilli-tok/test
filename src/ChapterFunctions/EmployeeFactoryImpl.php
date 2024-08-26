<?php
declare(strict_types=1);

namespace CleanCode\ChapterFunctions;

abstract class Employee {
    public abstract function isPayday(): bool;
    public abstract function calculatePay(): Money;
    public abstract function deliverPay(Money $pay): void;
}

interface EmployeeFactory {
    public function makeEmployee(EmployeeRecord $r): Employee;
}

class EmployeeFactoryImpl implements EmployeeFactory {
    public function makeEmployee($r): Employee
    {
        return match ($r->type) {
            'COMMISSIONED' => new CommissionedEmployee($r),
            'HOURLY' => new HourlyEmployee($r),
            'SALARIED' => new SalariedEmployee($r),
            default => new InvalidEmployeeType($r->type),
        };
        /**
        switch ($r->type) {
            case 'COMMISSIONED':
                return new CommissionedEmployee($r);
            case 'HOURLY':
                return new HourlyEmployee($r);
            case 'SALARIED':
                return new SalariedEmployee($r);
            default:
                throw new InvalidEmployeeType($r->type);
        }*/
    }
}

class Money {
    public string $type;
}

class EmployeeRecord {
    public string $type;
}
class CommissionedEmployee extends Employee {

    public function isPayday(): bool {
        return true;
    }

    public function calculatePay(): Money {
        return new Money();
    }

    public function deliverPay(Money $pay): void {
    }
}

class HourlyEmployee extends Employee {
    public function isPayday(): bool {
        return true;
    }

    public function calculatePay(): Money {
        return new Money();
    }

    public function deliverPay(Money $pay): void {
    }
}

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
class InvalidEmployeeType extends Exception {
    public function __construct($type) {
    }
}
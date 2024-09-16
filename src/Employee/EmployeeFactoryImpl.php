<?php
declare(strict_types=1);

namespace CleanCode\Employee;

class EmployeeFactoryImpl implements EmployeeFactory
{
    /**
     * @throws InvalidEmployeeType
     */
    public function makeEmployee(EmployeeRecord $r): Employee
    {
        return match ($r->type) {
            EmployeeType::COMMISSIONED => new CommissionedEmployee($r),
            EmployeeType::HOURLY => new HourlyEmployee($r),
            EmployeeType::SALARIED => new SalariedEmployee($r),
            default => throw new InvalidEmployeeType($r->type),
        };
    }
}
<?php
declare(strict_types=1);

namespace CleanCode\Employee;

class EmployeeFactoryImpl implements EmployeeFactory {
    /**
     * @param EmployeeRecord $r
     * @return CommissionedEmployee|HourlyEmployee|SalariedEmployee
     * @throws InvalidEmployeeType
     */
    public function makeEmployee(EmployeeRecord $r): CommissionedEmployee|HourlyEmployee|SalariedEmployee
    {
        return match ($r->type) {
            EmployeeType::COMMISSIONED => new CommissionedEmployee($r),
            EmployeeType::HOURLY => new HourlyEmployee($r),
            EmployeeType::SALARIED => new SalariedEmployee($r),
            default => throw new InvalidEmployeeType($r->type),
        };
    }
}
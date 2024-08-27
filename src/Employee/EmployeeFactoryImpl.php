<?php
declare(strict_types=1);

namespace CleanCode\Employee;

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
<?php
declare(strict_types=1);

namespace CleanCode\Employee;

interface EmployeeFactory
{
    /**
     * @throws InvalidEmployeeType
     */
    public function makeEmployee(EmployeeRecord $r): Employee;
}
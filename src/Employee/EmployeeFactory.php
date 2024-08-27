<?php
declare(strict_types=1);

namespace CleanCode\Employee;

interface EmployeeFactory {
    public function makeEmployee(EmployeeRecord $r): Employee;
}
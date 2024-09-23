<?php
declare(strict_types=1);

namespace CleanCode\Employee;

class EmployeeRecord
{

    public function __construct(public EmployeeType $type)
    {
    }
}
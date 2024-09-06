<?php
declare(strict_types=1);

namespace CleanCode\Employee;
use Exception;

class InvalidEmployeeType extends Exception {
    public function __construct(EmployeeType $type)
    {
        parent::__construct("Invalid employee type: " . $type->name);
    }
}
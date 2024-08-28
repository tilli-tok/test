<?php
declare(strict_types=1);

namespace CleanCode\Employee;
class InvalidEmployeeType{
    public function __construct(EmployeeType $type) {
        return 'Invalid Employee Type '.$type->name;
    }
}

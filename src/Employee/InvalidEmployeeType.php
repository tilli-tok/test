<?php
declare(strict_types=1);

namespace CleanCode\Employee;

class InvalidEmployeeType extends Exception {
    public function __construct($type) {
    }
}
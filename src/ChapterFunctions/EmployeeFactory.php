<?php
declare(strict_types=1);

namespace CleanCode\ChapterFunctions;

interface EmployeeFactory {
    public function makeEmployee(EmployeeRecord $r): Employee;
}
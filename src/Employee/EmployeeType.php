<?php
declare(strict_types=1);

namespace CleanCode\Employee;

enum EmployeeType
{
    case COMMISSIONED;
    case HOURLY;
    case SALARIED;
}
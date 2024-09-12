<?php

namespace CleanCode\Listing14;
enum ErrorCode: string
{
    case OK = 'TILT: Should not get here.';
    case INVALID_FORMAT = 'INVALID_FORMAT';
    case UNEXPECTED_ARGUMENT = 'Argument -%s unexpected.';
    case INVALID_ARGUMENT_NAME = '';
    case MISSING_STRING = 'Could not find string parameter for -%s.';
    case MISSING_INTEGER = 'Could not find integer parameter for -%s.';
    case INVALID_INTEGER = "Argument -%s expects an integer but was '%s'.";
    case MISSING_DOUBLE = 'Could not find double parameter for -%s.';
    case INVALID_DOUBLE = 'Argument -%s expects a double but was -%s';
}
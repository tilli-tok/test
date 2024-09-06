<?php

namespace CleanCode\Cleansing;

enum ErrorCode
{
    case OK;
    case INVALID_ARGUMENT_FORMAT;
    case UNEXPECTED_ARGUMENT;
    case INVALID_ARGUMENT_NAME;
    case MISSING_STRING;
    case MISSING_INTEGER;
    case INVALID_INTEGER;
    case MISSING_DOUBLE;
    case INVALID_DOUBLE;
}
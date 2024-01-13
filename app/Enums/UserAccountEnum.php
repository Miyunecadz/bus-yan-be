<?php

namespace App\Enums;

enum UserAccountEnum: string
{
    case ADMIN = 'admin';
    case BUS_OPERATOR = 'bus-operator';
    case BUS_COOPERATIVE = 'bus-cooperative';
    case DRIVER = 'driver';
    case JOBSEEKER = 'jobseeker';
}

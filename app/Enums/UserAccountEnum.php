<?php

namespace App\Enums;

enum UserAccountEnum: string
{
    case ADMIN = 'admin';
    case BUS_OPERATOR = 'bus-operator';
    case DRIVER = 'driver';
    case JOBSEEKER = 'jobseeker';
}

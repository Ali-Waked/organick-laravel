<?php

namespace   App\Enums;

enum PaymentGatewayMethodStatus: string
{
    case Active = 'active';
    case InActive = 'in-active';
}

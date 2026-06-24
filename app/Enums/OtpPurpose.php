<?php

namespace App\Enums;

enum OtpPurpose: string
{
    case TwoFactorSetup = 'two_factor_setup';

    case EmailVerification = 'email_verification';

    case TwoFactorDisable = 'two_factor_disable';

    case TwoFactorLogin = 'two_factor_login';

    case PinChange = 'pin_change';

    public function label(): string
    {
        return convert_text($this->value);
    }
}

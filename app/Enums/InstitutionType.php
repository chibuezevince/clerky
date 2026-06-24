<?php

namespace App\Enums;

enum InstitutionType: string
{
    case University = 'university';

    case Polytechnic = 'polytechnic';

    case College = 'college';

    case Others = 'others';
}

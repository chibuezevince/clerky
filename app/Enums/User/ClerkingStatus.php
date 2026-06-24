<?php

namespace App\Enums\User;

enum ClerkingStatus: string
{
    case Draft = 'draft';

    case InProgress = 'in_progress';

    case Complete = 'complete';

    case Archived = 'archived';
}

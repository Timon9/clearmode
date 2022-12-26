<?php

namespace App\Enums;

/**
 * Enum class for roles in Team
 */

enum TeamRoleEnum: string
{
    case ADMIN = 'ADMIN';
    case MEMBER = 'MEMBER';
}

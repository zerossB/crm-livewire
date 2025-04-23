<?php

namespace App\Enums;

use App\Traits\EnumHelper;

enum Can: string
{
    use EnumHelper;

    case BE_AN_ADMIN = 'be an admin';

    public function getLabel(): string
    {
        return match ($this) {
            self::BE_AN_ADMIN => 'Be an admin',
        };
    }
}

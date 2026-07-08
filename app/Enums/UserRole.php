<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Kurator = 'kurator';
    case Buyer = 'buyer';

    public function label(): string
    {
        return match($this) {
            self::Admin => 'Admin',
            self::Kurator => 'Kurator',
            self::Buyer => 'Buyer',
        };
    }
}

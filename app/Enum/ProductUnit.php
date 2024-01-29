<?php

namespace App\Enum;

enum ProductUnit: string
{
    case KG = 'کیلوگرم';
    case G = 'گرم';

    public static function fromName(string $name)
    {
        return constant("self::$name");
    }
}

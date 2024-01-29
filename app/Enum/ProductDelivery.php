<?php

namespace App\Enum;

enum ProductDelivery: string
{
    case store = 'انبار';
    case factory = 'کارخانه';

    public static function fromName(string $name)
    {
        return constant("self::$name");
    }
}

<?php

namespace App\Enum;

enum CommentType: string
{
    case product = 'product';
    case article = 'article';
    case size = 'size';
    case factory = 'factory';
    case standard = 'standard';

    public static function fromName(string $name)
    {
        return constant("self::$name");
    }
}

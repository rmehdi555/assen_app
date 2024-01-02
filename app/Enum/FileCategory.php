<?php

namespace App\Enum;

enum FileCategory: int
{
    case tickets = 1;
    case articles = 2;
    case products = 3;

    public function entity(): array
    {
        return match ($this) {
            self::tickets => [
                'path' => '/tickets/',
                'types' => 'image/jpeg,image/png,image/jpg,image/svg,image/gif',
                'fileSystem' => 'public',
                'maxUploadSize' => '5000000'
            ],
            self::articles => [
                'path' => '/articles/',
                'types' => 'image/jpeg,image/png,image/jpg,image/svg,image/gif',
                'fileSystem' => 'public',
                'maxUploadSize' => '5000000'
            ],
            self::products => [
                'path' => '/products/',
                'types' => 'image/jpeg,image/png,image/jpg,image/svg,image/gif',
                'fileSystem' => 'public',
                'maxUploadSize' => '5000000'
            ],
        };
    }
}

<?php

namespace App\Constants;

class Manufacturers
{
    const LIST = [
        'toyota' => [
            'name' => 'Toyota',
            'logo' => 'manufacturers/toyota.png'
        ],
        'nissan' => [
            'name' => 'Nissan',
            'logo' => 'manufacturers/nissan.png'
        ],
        'honda' => [
            'name' => 'Honda',
            'logo' => 'manufacturers/honda.png'
        ],
        'ford' => [
            'name' => 'Ford',
            'logo' => 'manufacturers/ford.png'
        ],
        'chevrolet' => [
            'name' => 'Chevrolet',
            'logo' => 'manufacturers/chevrolet.png'
        ],
        'bmw' => [
            'name' => 'BMW',
            'logo' => 'manufacturers/bmw.png'
        ],
        'mercedes' => [
            'name' => 'Mercedes-Benz',
            'logo' => 'manufacturers/mercedes.png'
        ],
        'volkswagen' => [
            'name' => 'Volkswagen',
            'logo' => 'manufacturers/volkswagen.png'
        ],
    ];

    public static function getNames(): array
    {
        return array_combine(
            array_keys(self::LIST),
            array_column(self::LIST, 'name')
        );
    }

    public static function getLogo(string $manufacturer): string
    {
        return self::LIST[$manufacturer]['logo'] ?? '';
    }
}
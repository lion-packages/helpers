<?php

declare(strict_types=1);

namespace Tests\Providers;

trait ArrProviderTrait
{
    const ROOT = 'root';
    const DEV = 'dev';
    const LIMIT = 10;
    const LENGTH = 1;
    const ONE = self::LENGTH;
    const TWO = 2;
    const THREE = 3;
    const NAMES = ['name' => 'root'];
    const NAMES_PUSH = ['name' => 'root', 'username' => 'dev'];
    const NAMES_JOINS = ['name' => 'root', 'username' => 'dev', 'test' => 'testing'];
    const ELEMENTS = [9, 1, 5, 6, 4, 2, 8, 0, 3, 7];
    const ELEMENTS_WHERE_NOT_EMPTY = ['', null, self::ROOT, self::DEV];
    const ELEMENTS_WHERE_NOT_EMPTY_FILTER = [2 => self::ROOT, 3 => self::DEV];

    public static function joinProvider(): array
    {
        return [
            [
                'elements' => self::NAMES_JOINS,
                'separator' => ', ',
                'lastSeparator' => null,
                'return' => 'root, dev, testing'
            ],
            [
                'elements' => self::NAMES_JOINS,
                'separator' => ', ',
                'lastSeparator' => ' and ',
                'return' => 'root, dev and testing'
            ],
            [
                'elements' => self::NAMES_PUSH,
                'separator' => ', ',
                'lastSeparator' => ' and ',
                'return' => 'root and dev'
            ],
            [
                'elements' => self::NAMES_PUSH,
                'separator' => ', ',
                'lastSeparator' => null,
                'return' => 'root, dev'
            ],
            [
                'elements' => self::NAMES,
                'separator' => ', ',
                'lastSeparator' => null,
                'return' => 'root'
            ],
            [
                'elements' => self::NAMES,
                'separator' => ', ',
                'lastSeparator' => ' and ',
                'return' => 'root'
            ]
        ];
    }

    public static function randomProvider(): array
    {
        return [
            [
                'list' => [self::ROOT],
                'limit' => self::ONE
            ],
            [
                'list' => self::NAMES,
                'limit' => self::ONE
            ],
            [
                'list' => self::NAMES_PUSH,
                'limit' => self::TWO
            ],
        ];
    }

    public static function whereProvider(): array
    {
        return [
            [
                'return' => [
                    0 => 9,
                    3 => 6,
                    6 => 8,
                    9 => 7
                ],
                'callback' => fn($value) => $value > 5
            ],
            [
                'return' => [
                    6 => 8,
                    9 => 7
                ],
                'callback' => fn($value, $key) => $key > 4 && $value > 5
            ],
            [
                'return' => [
                    0 => 9,
                    3 => 6,
                    6 => 8,
                    9 => 7
                ],
                'callback' => static fn($value) => $value > 5
            ],
            [
                'return' => [
                    6 => 8,
                    9 => 7
                ],
                'callback' => static fn($value, $key) => $key > 4 && $value > 5
            ]
        ];
    }

    public static function wrapProvider(): array
    {
        return [
            [
                'value' => null,
                'return' => []
            ],
            [
                'value' => '',
                'return' => []
            ],
            [
                'value' => self::ROOT,
                'return' => [self::ROOT]
            ],
            [
                'value' => [self::ROOT, self::DEV],
                'return' => [[self::ROOT, self::DEV]]
            ]
        ];
    }
}

<?php

declare(strict_types=1);

namespace Tests\Providers;

use Closure;

trait ArrProviderTrait
{
    private const string ROOT = 'root';
    private const string DEV = 'dev';
    private const int LIMIT = 10;
    private const int LENGTH = 1;
    private const int ONE = self::LENGTH;
    private const int TWO = 2;
    private const array NAMES = ['name' => 'root'];
    private const array NAMES_PUSH = [
        'name' => 'root',
        'username' => 'dev',
    ];
    private const array NAMES_JOINS = [
        'name' => 'root',
        'username' => 'dev',
        'test' => 'testing',
    ];
    private const array ELEMENTS = [9, 1, 5, 6, 4, 2, 8, 0, 3, 7];
    private const array ELEMENTS_WHERE_NOT_EMPTY = ['', null, self::ROOT, self::DEV];
    private const array ELEMENTS_WHERE_NOT_EMPTY_FILTER = [2 => self::ROOT, 3 => self::DEV];

    /**
     * @return array<int, array<string, string|array<string, string>|null>>
     */
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

    /**
     * @return array<int, array<string, int|array<int|string, string>>>
     */
    public static function randomProvider(): array
    {
        return [
            [
                'randomList' => [self::ROOT],
                'limit' => self::ONE
            ],
            [
                'randomList' => self::NAMES,
                'limit' => self::ONE
            ],
            [
                'randomList' => self::NAMES_PUSH,
                'limit' => self::TWO
            ],
        ];
    }

    /**
     * @return array<int, array<string, Closure|array<int, int>>>
     */
    public static function whereProvider(): array
    {
        return [
            [
                'return' => [
                    0 => 9,
                    3 => 6,
                    6 => 8,
                    9 => 7,
                ],
                'callback' => fn ($value) => $value > 5,
            ],
            [
                'return' => [
                    6 => 8,
                    9 => 7,
                ],
                'callback' => fn ($value, $key) => $key > 4 && $value > 5,
            ],
            [
                'return' => [
                    0 => 9,
                    3 => 6,
                    6 => 8,
                    9 => 7,
                ],
                'callback' => static fn ($value) => $value > 5,
            ],
            [
                'return' => [
                    6 => 8,
                    9 => 7,
                ],
                'callback' => static fn ($value, $key) => $key > 4 && $value > 5,
            ],
        ];
    }

    /**
     * @return array<int, array<string, string|array<int, string|array<int, string>>|null>>
     */
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

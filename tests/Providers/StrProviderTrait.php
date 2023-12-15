<?php

declare(strict_types=1);

namespace Tests\Providers;

trait StrProviderTrait
{
    public static function maskProvider(): array
    {
        return [
            [
                'value' => 'root',
                'char' => '*',
                'length' => -2,
                'return' => '**ot'
            ],
            [
                'value' => 'root',
                'char' => '-',
                'length' => -2,
                'return' => '--ot'
            ],
            [
                'value' => 'root',
                'char' => '+',
                'length' => -2,
                'return' => '++ot'
            ],
            [
                'value' => 'root',
                'char' => '&',
                'length' => -2,
                'return' => '&&ot'
            ],
            [
                'value' => 'root',
                'char' => '_',
                'length' => -2,
                'return' => '__ot'
            ],
            [
                'value' => 'root',
                'char' => '@',
                'length' => -2,
                'return' => '@@ot'
            ],
            [
                'value' => 'root',
                'char' => '/',
                'length' => -2,
                'return' => '//ot'
            ],
            [
                'value' => 'root',
                'char' => '*',
                'length' => 2,
                'return' => 'ro**'
            ],
            [
                'value' => 'root',
                'char' => '-',
                'length' => 2,
                'return' => 'ro--'
            ],
            [
                'value' => 'root',
                'char' => '+',
                'length' => 2,
                'return' => 'ro++'
            ],
            [
                'value' => 'root',
                'char' => '&',
                'length' => 2,
                'return' => 'ro&&'
            ],
            [
                'value' => 'root',
                'char' => '_',
                'length' => 2,
                'return' => 'ro__'
            ],
            [
                'value' => 'root',
                'char' => '@',
                'length' => 2,
                'return' => 'ro@@'
            ],
            [
                'value' => 'root',
                'char' => '/',
                'length' => 2,
                'return' => 'ro//'
            ]
        ];
    }

    public static function containsProvider(): array
    {
        return [
            [
                'words' => ['hello'],
                'return' => true
            ],
            [
                'words' => ['lion'],
                'return' => true
            ],
            [
                'words' => ['-'],
                'return' => true
            ],
            [
                'words' => ['helpers'],
                'return' => true
            ],
            [
                'words' => ['hello', 'helpers', '-', 'hello'],
                'return' => true
            ],
            [
                'words' => ['dev'],
                'return' => false
            ],
            [
                'words' => ['@hello'],
                'return' => false
            ],
            [
                'words' => ['last'],
                'return' => false
            ],
            [
                'words' => ['first'],
                'return' => false
            ],
            [
                'words' => ['dev', '@hello', 'last', 'first'],
                'return' => false
            ]
        ];
    }

    public static function t3stProvider(): array
    {
        return [
            [
                'value' => 'root',
                'expr' => "/root/i",
                'return' => true
            ],
            [
                'value' => 'dev',
                'expr' => "/dev/i",
                'return' => true
            ],
            [
                'value' => 'dev',
                'expr' => "/root/i",
                'return' => false
            ],
            [
                'value' => 'root',
                'expr' => "/dev/i",
                'return' => false
            ]
        ];
    }

    public static function trimProvider(): array
    {
        return [
            [
                'desc' => ' hello lion - lion/helpers ',
                'return' => 'hello lion - lion/helpers',
                'replace' => ''
            ],
            [
                'desc' => ' hello lion - lion/helpers ',
                'return' => 'hello lion  lion/helpers',
                'replace' => '-'
            ]
        ];
    }

    public static function concatProvider(): array
    {
        return [
            [
                'desc' => 'hello',
                'concat' => 'world',
                'return' => 'hello world'
            ],
            [
                'desc' => 'hello',
                'concat' => 'root',
                'return' => 'hello root'
            ],
            [
                'desc' => 'hello',
                'concat' => 'dev',
                'return' => 'hello dev'
            ],
            [
                'desc' => 'test',
                'concat' => 'dev',
                'return' => 'test dev'
            ]
        ];
    }
}

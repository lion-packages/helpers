<?php

declare(strict_types=1);

namespace Tests;

use Closure;
use InvalidArgumentException;
use Lion\Test\Test;
use Lion\Helpers\Arr;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Providers\ArrProviderTrait;

class ArrTest extends Test
{
    use ArrProviderTrait;

    private const string ID = 'id';
    private const array VALUES = ['root'];
    private const array KEYS_NAMES = ['name'];
    private const string USERNAME = 'username';
    private const string DEV = 'dev';
    private const string TESTING = 'testing';
    private const string TEST = 'test';
    private const string AAA = 'AAA';
    private const string ABA = 'ABA';
    private const string ACA = 'ACA';
    private const array KEY_LIST = [
        [self::ID => self::AAA, ...self::NAMES_JOINS],
        [self::ID => self::ABA, ...self::NAMES_JOINS],
        [self::ID => self::ACA, ...self::NAMES_JOINS]
    ];
    private const array KEY_BY_LIST = [
        self::AAA => [self::ID => self::AAA, ...self::NAMES_JOINS],
        self::ABA => [self::ID => self::ABA, ...self::NAMES_JOINS],
        self::ACA => [self::ID => self::ACA, ...self::NAMES_JOINS]
    ];
    private const array TREE_LIST = [
        [self::ID => self::AAA, ...self::NAMES_JOINS],
        [self::ID => self::ABA, ...self::NAMES_JOINS],
        [self::ID => self::ACA, ...self::NAMES_JOINS],
        [self::ID => self::AAA, ...self::NAMES_JOINS],
        [self::ID => self::ABA, ...self::NAMES_JOINS],
        [self::ID => self::ACA, ...self::NAMES_JOINS]
    ];
    private const array TREE_BY_LIST = [
        self::AAA => [
            [self::ID => self::AAA, ...self::NAMES_JOINS],
            [self::ID => self::AAA, ...self::NAMES_JOINS]
        ],
        self::ABA => [
            [self::ID => self::ABA, ...self::NAMES_JOINS],
            [self::ID => self::ABA, ...self::NAMES_JOINS]
        ],
        self::ACA => [
            [self::ID => self::ACA, ...self::NAMES_JOINS],
            [self::ID => self::ACA, ...self::NAMES_JOINS]
        ]
    ];

    private Arr $arr;

    protected function setUp(): void
    {
        $this->arr = new Arr();
    }

    public function testOf(): void
    {
        $arr = $this->arr->of(self::NAMES);

        $this->assertInstanceOf(Arr::class, $arr);
        $this->assertSame($this->arr, $arr);
    }

    public function testToObject(): void
    {
        $arr = $this->arr->of(self::NAMES)->toObject()->get();

        $this->assertIsObject($arr);
    }

    public function testKeys(): void
    {
        $arr = $this->arr->of(self::NAMES)->keys()->get();

        $this->assertIsArray($arr);
        $this->assertSame(self::KEYS_NAMES, $arr);
    }

    public function testValues(): void
    {
        $arr = $this->arr->of(self::NAMES)->values()->get();

        $this->assertIsArray($arr);
        $this->assertSame(self::VALUES, $arr);
    }

    public function testGet(): void
    {
        $arr = $this->arr->of(self::NAMES)->get();

        $this->assertIsArray($arr);
        $this->assertSame(self::NAMES, $arr);
    }

    public function testPush(): void
    {
        $arr = $this->arr->of(self::NAMES)->push(self::DEV, self::USERNAME)->get();

        $this->assertIsArray($arr);
        $this->assertSame(self::NAMES_PUSH, $arr);
    }

    public function testLength(): void
    {
        $length = $this->arr->of(self::NAMES)->length();

        $this->assertIsInt($length);
        $this->assertSame(self::LENGTH, $length);
    }

    #[DataProvider('joinProvider')]
    public function testJoin(array $elements, string $separator, ?string $lastSeparator, string $return): void
    {
        $str = $this->arr->of($elements)->join($separator, $lastSeparator);

        $this->assertIsString($str);
        $this->assertSame($return, $str);
    }

    public function testKeyBy(): void
    {
        $arr = $this->arr->of(self::KEY_LIST)->keyBy(self::ID)->get();

        $this->assertIsArray($arr);
        $this->assertSame(self::KEY_BY_LIST, $arr);
    }

    public function testTree(): void
    {
        $arr = $this->arr->of(self::TREE_LIST)->tree(self::ID)->get();

        $this->assertIsArray($arr);
        $this->assertSame(self::TREE_BY_LIST, $arr);
    }

    public function testPrepend(): void
    {
        $arr = $this->arr->of(self::NAMES)->prepend('dev', 'developer')->get();

        $this->assertIsArray($arr);
        $this->assertArrayHasKey('name', $arr);
        $this->assertArrayHasKey('developer', $arr);
        $this->assertSame(self::ROOT, $arr['name']);
        $this->assertSame(self::DEV, $arr['developer']);
    }

    #[DataProvider('randomProvider')]
    public function testRandom(array $list, int $limit): void
    {
        $arr = $this->arr->of($list)->random($limit)->get();

        $this->assertIsArray($arr);
        $this->assertCount($limit, $arr);
    }

    public function testMultipleRandom(): void
    {
        $arr = $this->arr->of([self::ROOT, self::DEV, 'test', 'lion'])->random(self::TWO)->get();

        $this->assertIsArray($arr);
        $this->assertCount(self::TWO, $arr);
    }

    public function testRandomException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->arr->of([self::ROOT])->random(self::LIMIT)->get();
    }

    #[DataProvider('whereProvider')]
    public function testWhere(array $return, Closure $callback): void
    {
        $arr = $this->arr->of(self::ELEMENTS)->where($callback)->get();

        $this->assertIsArray($arr);
        $this->assertSame($return, $arr);
    }

    public function testWhereNotEmpty(): void
    {
        $arr = $this->arr->of(self::ELEMENTS_WHERE_NOT_EMPTY)->whereNotEmpty()->get();

        $this->assertIsArray($arr);
        $this->assertSame(self::ELEMENTS_WHERE_NOT_EMPTY_FILTER, $arr);
    }

    public function testFirst(): void
    {
        $this->assertSame(self::ROOT, $this->arr->of(self::NAMES_PUSH)->first());
    }

    public function testLast(): void
    {
        $this->assertSame(self::DEV, $this->arr->of(self::NAMES_PUSH)->last());
    }

    #[DataProvider('wrapProvider')]
    public function testWrap(mixed $value, array $return): void
    {
        $arr = $this->arr->wrap($value)->get();

        $this->assertIsArray($arr);
        $this->assertSame($return, $arr);
    }
}

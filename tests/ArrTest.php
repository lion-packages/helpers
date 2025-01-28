<?php

declare(strict_types=1);

namespace Tests;

use Closure;
use InvalidArgumentException;
use Lion\Test\Test;
use Lion\Helpers\Arr;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test as Testing;
use Tests\Providers\ArrProviderTrait;

class ArrTest extends Test
{
    use ArrProviderTrait;

    private const string ID = 'id';
    private const array VALUES = ['root'];
    private const array KEYS_NAMES = ['name'];
    private const string USERNAME = 'username';
    private const string DEV = 'dev';
    private const string AAA = 'AAA';
    private const string ABA = 'ABA';
    private const string ACA = 'ACA';
    private const array KEY_LIST = [
        [self::ID => self::AAA, ...self::NAMES_JOINS],
        [self::ID => self::ABA, ...self::NAMES_JOINS],
        [self::ID => self::ACA, ...self::NAMES_JOINS],
    ];
    private const array KEY_BY_LIST = [
        self::AAA => [self::ID => self::AAA, ...self::NAMES_JOINS],
        self::ABA => [self::ID => self::ABA, ...self::NAMES_JOINS],
        self::ACA => [self::ID => self::ACA, ...self::NAMES_JOINS],
    ];
    private const array TREE_LIST = [
        [self::ID => self::AAA, ...self::NAMES_JOINS],
        [self::ID => self::ABA, ...self::NAMES_JOINS],
        [self::ID => self::ACA, ...self::NAMES_JOINS],
        [self::ID => self::AAA, ...self::NAMES_JOINS],
        [self::ID => self::ABA, ...self::NAMES_JOINS],
        [self::ID => self::ACA, ...self::NAMES_JOINS],
    ];
    private const array TREE_BY_LIST = [
        self::AAA => [
            [self::ID => self::AAA, ...self::NAMES_JOINS],
            [self::ID => self::AAA, ...self::NAMES_JOINS],
        ],
        self::ABA => [
            [self::ID => self::ABA, ...self::NAMES_JOINS],
            [self::ID => self::ABA, ...self::NAMES_JOINS],
        ],
        self::ACA => [
            [self::ID => self::ACA, ...self::NAMES_JOINS],
            [self::ID => self::ACA, ...self::NAMES_JOINS],
        ]
    ];

    private Arr $arr;

    protected function setUp(): void
    {
        $this->arr = new Arr();
    }

    #[Testing]
    public function of(): void
    {
        $arr = $this->arr->of(self::NAMES);

        $this->assertInstanceOf(Arr::class, $arr);
        $this->assertSame($this->arr, $arr);
    }

    #[Testing]
    public function toObject(): void
    {
        $arr = $this->arr->of(self::NAMES)->toObject()->get();

        $this->assertIsObject($arr);
    }

    #[Testing]
    public function keys(): void
    {
        $arr = $this->arr->of(self::NAMES)->keys()->get();

        $this->assertIsArray($arr);
        $this->assertSame(self::KEYS_NAMES, $arr);
    }

    #[Testing]
    public function values(): void
    {
        $arr = $this->arr->of(self::NAMES)->values()->get();

        $this->assertIsArray($arr);
        $this->assertSame(self::VALUES, $arr);
    }

    #[Testing]
    public function get(): void
    {
        $arr = $this->arr->of(self::NAMES)->get();

        $this->assertIsArray($arr);
        $this->assertSame(self::NAMES, $arr);
    }

    #[Testing]
    public function push(): void
    {
        $arr = $this->arr->of(self::NAMES)->push(self::DEV, self::USERNAME)->get();

        $this->assertIsArray($arr);
        $this->assertSame(self::NAMES_PUSH, $arr);
    }

    #[Testing]
    public function length(): void
    {
        $length = $this->arr->of(self::NAMES)->length();

        $this->assertSame(self::LENGTH, $length);
    }

    /**
     * @param array<string, string> $elements [List of words]
     * @param string $separator [Separation value]
     * @param string|null $lastSeparator [End Separator]
     * @param string $return [Return value]
     *
     * @return void
     */
    #[Testing]
    #[DataProvider('joinProvider')]
    public function join(array $elements, string $separator, ?string $lastSeparator, string $return): void
    {
        $str = $this->arr->of($elements)->join($separator, $lastSeparator);

        $this->assertSame($return, $str);
    }

    #[Testing]
    public function keyBy(): void
    {
        $arr = $this->arr->of(self::KEY_LIST)->keyBy(self::ID)->get();

        $this->assertIsArray($arr);
        $this->assertSame(self::KEY_BY_LIST, $arr);
    }

    #[Testing]
    public function tree(): void
    {
        $arr = $this->arr->of(self::TREE_LIST)->tree(self::ID)->get();

        $this->assertIsArray($arr);
        $this->assertSame(self::TREE_BY_LIST, $arr);
    }

    #[Testing]
    public function prepend(): void
    {
        $arr = $this->arr->of(self::NAMES)->prepend('dev', 'developer')->get();

        $this->assertIsArray($arr);
        $this->assertArrayHasKey('name', $arr);
        $this->assertArrayHasKey('developer', $arr);
        $this->assertSame(self::ROOT, $arr['name']);
        $this->assertSame(self::DEV, $arr['developer']);
    }

    /**
     * @param array<int|string, string> $randomList [List of random items]
     * @param int $limit [Limit value]
     *
     * @return void
     */
    #[Testing]
    #[DataProvider('randomProvider')]
    public function random(array $randomList, int $limit): void
    {
        $arr = $this->arr->of($randomList)->random($limit)->get();

        $this->assertIsArray($arr);
        $this->assertCount($limit, $arr);
    }

    #[Testing]
    public function multipleRandom(): void
    {
        $arr = $this->arr->of([self::ROOT, self::DEV, 'test', 'lion'])->random(self::TWO)->get();

        $this->assertIsArray($arr);
        $this->assertCount(self::TWO, $arr);
    }

    #[Testing]
    public function randomException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->arr->of([self::ROOT])->random(self::LIMIT)->get();
    }

    /**
     * @param array<int, int> $return
     * @param Closure $callback [Filter method]
     *
     * @return void
     */
    #[Testing]
    #[DataProvider('whereProvider')]
    public function where(array $return, Closure $callback): void
    {
        $arr = $this->arr->of(self::ELEMENTS)->where($callback)->get();

        $this->assertIsArray($arr);
        $this->assertSame($return, $arr);
    }

    #[Testing]
    public function whereNotEmpty(): void
    {
        $arr = $this->arr->of(self::ELEMENTS_WHERE_NOT_EMPTY)->whereNotEmpty()->get();

        $this->assertIsArray($arr);
        $this->assertSame(self::ELEMENTS_WHERE_NOT_EMPTY_FILTER, $arr);
    }

    #[Testing]
    public function first(): void
    {
        $this->assertSame(self::ROOT, $this->arr->of(self::NAMES_PUSH)->first());
    }

    #[Testing]
    public function last(): void
    {
        $this->assertSame(self::DEV, $this->arr->of(self::NAMES_PUSH)->last());
    }

    /**
     * @param mixed $value [Value inserted]
     * @param array<int, string|array<int, string>> $return [Return list]
     *
     * @return void
     */
    #[Testing]
    #[DataProvider('wrapProvider')]
    public function wrap(mixed $value, array $return): void
    {
        $arr = $this->arr->wrap($value)->get();

        $this->assertIsArray($arr);
        $this->assertSame($return, $arr);
    }
}

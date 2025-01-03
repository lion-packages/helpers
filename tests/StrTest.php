<?php

declare(strict_types=1);

namespace Tests;

use Lion\Test\Test;
use Lion\Helpers\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test as Testing;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\Providers\StrProviderTrait;

class StrTest extends Test
{
    use StrProviderTrait;

    private const string NAME = 'root';
    private const string NAME_REPLACE = 'dev';
    private const string PREPEND = 'lion-';
    private const int LENGHT = 4;
    private const string LN = "\n";
    private const string LT = "\t";
    private const string DESCRIPTION = 'hello lion - lion/helpers';
    private const string DESCRIPTION_SWAP = 'hi dev - dev/test';
    private const string BEFORE = 'hello lion ';
    private const string AFTER = ' lion/helpers';
    private const string BETWEEN = ' lion - lion/';
    private const string HELLO = 'hello';
    private const string HELPERS = 'helpers';
    private const string CAMEL = 'lionRoot';
    private const string PASCAL = 'LionRoot';
    private const string SNAKE = 'lion_root';
    private const string KEBAB = 'lion-root';
    private const string HEADLINE = 'Lion Root';
    private const string LIMIT = 'ro';
    private const string LOWER = 'lion';
    private const string UPPER = 'LION';
    private const array SWAP_REPLACE = ['hello' => 'hi', 'helpers' => 'test', 'lion' => 'dev'];

    private Str $str;

    protected function setUp(): void
    {
        $this->str = new Str();
    }

    #[Testing]
    public function get(): void
    {
        $this->assertSame(self::NAME, $this->str->of(self::NAME)->get());
    }

    #[Testing]
    public function of(): void
    {
        $str = $this->str->of(self::NAME);

        $this->assertInstanceOf(Str::class, $str);
        $this->assertSame($this->str, $str);
    }

    #[Testing]
    public function split(): void
    {
        $list = $this->str->of(self::NAME)->split(' ');

        $this->assertIsArray($list);
        $this->assertSame([self::NAME], $list);
    }

    #[Testing]
    public function spaces(): void
    {
        $str = $this->str->of(self::NAME)->spaces()->get();

        $this->assertIsString($str);
        $this->assertSame(self::NAME . ' ', $str);
    }

    #[Testing]
    public function replace(): void
    {
        $str = $this->str->of(self::NAME)->replace(self::NAME, self::NAME_REPLACE)->get();

        $this->assertIsString($str);
        $this->assertSame(self::NAME_REPLACE, $str);
    }

    #[Testing]
    public function prepend(): void
    {
        $str = $this->str->of(self::NAME)->prepend(self::PREPEND)->get();

        $this->assertIsString($str);
        $this->assertSame(self::PREPEND . self::NAME, $str);
    }

    #[Testing]
    public function ln(): void
    {
        $str = $this->str->of(self::NAME)->ln()->get();

        $this->assertIsString($str);
        $this->assertStringContainsString(self::LN, $str);
    }

    #[Testing]
    public function lt(): void
    {
        $str = $this->str->of(self::NAME)->lt()->get();

        $this->assertIsString($str);
        $this->assertStringContainsString(self::LT, $str);
    }

    #[Testing]
    #[TestWith(['of' => null])]
    #[TestWith(['of' => ''])]
    public function toNull(?string $of): void
    {
        $str = $this->str->of($of)->toNull()->get();

        $this->assertNull($str);
    }

    #[Testing]
    public function toNullWithString(): void
    {
        $str = $this->str->of(self::NAME)->toNull()->get();

        $this->assertIsString($str);
        $this->assertSame(self::NAME, $str);
    }

    #[Testing]
    public function before(): void
    {
        $str = $this->str->of(self::DESCRIPTION)->before('-')->get();

        $this->assertIsString($str);
        $this->assertSame(self::BEFORE, $str);
    }

    #[Testing]
    public function after(): void
    {
        $str = $this->str->of(self::DESCRIPTION)->after('-')->get();

        $this->assertIsString($str);
        $this->assertSame(self::AFTER, $str);
    }

    #[Testing]
    public function between(): void
    {
        $str = $this->str->of(self::DESCRIPTION)->between(self::HELLO, self::HELPERS)->get();

        $this->assertIsString($str);
        $this->assertSame(self::BETWEEN, $str);
    }

    #[Testing]
    public function camel(): void
    {
        $str = $this->str->of(self::PREPEND . self::NAME)->replace('-', ' ')->camel()->get();

        $this->assertIsString($str);
        $this->assertSame(self::CAMEL, $str);
    }

    #[Testing]
    public function pascal(): void
    {
        $str = $this->str->of(self::PREPEND . self::NAME)->replace('-', ' ')->pascal()->get();

        $this->assertIsString($str);
        $this->assertSame(self::PASCAL, $str);
    }

    #[Testing]
    public function snake(): void
    {
        $str = $this->str->of(self::PREPEND . self::NAME)->snake(['-'])->get();

        $this->assertIsString($str);
        $this->assertSame(self::SNAKE, $str);
    }

    #[Testing]
    public function kebab(): void
    {
        $str = $this->str->of(self::PREPEND . self::NAME)->kebab(['-'])->get();

        $this->assertIsString($str);
        $this->assertSame(self::KEBAB, $str);
    }

    #[Testing]
    public function headline(): void
    {
        $str = $this->str->of(self::PREPEND . self::NAME)->replace('-', ' ')->headline()->get();

        $this->assertIsString($str);
        $this->assertSame(self::HEADLINE, $str);
    }

    #[Testing]
    public function length(): void
    {
        $length = $this->str->of(self::NAME)->length();

        $this->assertIsInt($length);
        $this->assertSame(self::LENGHT, $length);
    }

    #[Testing]
    public function limit(): void
    {
        $str = $this->str->of(self::NAME)->limit(2)->get();

        $this->assertIsString($str);
        $this->assertSame(self::LIMIT, $str);
    }

    #[Testing]
    public function lower(): void
    {
        $str = $this->str->of(self::UPPER)->lower()->get();

        $this->assertIsString($str);
        $this->assertSame(self::LOWER, $str);
    }

    #[Testing]
    public function upper(): void
    {
        $str = $this->str->of(self::LOWER)->upper()->get();

        $this->assertIsString($str);
        $this->assertSame(self::UPPER, $str);
    }

    #[Testing]
    #[DataProvider('maskProvider')]
    public function maskInit(string $value, string $char, int $length, string $return): void
    {
        $str = $this->str->of($value)->mask($char, $length)->get();

        $this->assertIsString($str);
        $this->assertSame($return, $str);
    }

    #[Testing]
    #[DataProvider('containsProvider')]
    public function contains(array $words, bool $return): void
    {
        $validate = $this->str->of(self::DESCRIPTION)->contains($words);

        $this->assertIsBool($validate);
        $this->assertSame($return, $validate);
    }

    #[Testing]
    public function swap(): void
    {
        $str = $this->str->of(self::DESCRIPTION)->swap(self::SWAP_REPLACE)->get();

        $this->assertIsString($str);
        $this->assertSame(self::DESCRIPTION_SWAP, $str);
    }

    #[Testing]
    #[DataProvider('t3stProvider')]
    public function test(string $value, string $expr, bool $return): void
    {
        $validate = $this->str->of($value)->test($expr);

        $this->assertIsBool($validate);
        $this->assertSame($return, $validate);
    }

    #[Testing]
    #[DataProvider('trimProvider')]
    public function trim(string $desc, string $return, string $replace): void
    {
        $str = $this->str->of($desc)->trim($replace)->get();

        $this->assertIsString($str);
        $this->assertSame($return, $str);
    }

    #[Testing]
    #[DataProvider('concatProvider')]
    public function concat(string $desc, string $concat, string $return): void
    {
        $str = $this->str->of($desc)->spaces()->concat($concat)->get();

        $this->assertIsString($str);
        $this->assertSame($return, $str);
    }
}

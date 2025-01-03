<?php

declare(strict_types=1);

namespace Tests;

use Lion\Test\Test;
use Lion\Helpers\Str;
use PHPUnit\Framework\Attributes\DataProvider;
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

    public function testGet(): void
    {
        $this->assertSame(self::NAME, $this->str->of(self::NAME)->get());
    }

    public function testOf(): void
    {
        $str = $this->str->of(self::NAME);

        $this->assertInstanceOf(Str::class, $str);
        $this->assertSame($this->str, $str);
    }

    public function testSplit(): void
    {
        $list = $this->str->of(self::NAME)->split(' ');

        $this->assertIsArray($list);
        $this->assertSame([self::NAME], $list);
    }

    public function testSpaces(): void
    {
        $str = $this->str->of(self::NAME)->spaces()->get();

        $this->assertIsString($str);
        $this->assertSame(self::NAME . ' ', $str);
    }

    public function testReplace(): void
    {
        $str = $this->str->of(self::NAME)->replace(self::NAME, self::NAME_REPLACE)->get();

        $this->assertIsString($str);
        $this->assertSame(self::NAME_REPLACE, $str);
    }

    public function testPrepend(): void
    {
        $str = $this->str->of(self::NAME)->prepend(self::PREPEND)->get();

        $this->assertIsString($str);
        $this->assertSame(self::PREPEND . self::NAME, $str);
    }

    public function testLn(): void
    {
        $str = $this->str->of(self::NAME)->ln()->get();

        $this->assertIsString($str);
        $this->assertStringContainsString(self::LN, $str);
    }

    public function testLt(): void
    {
        $str = $this->str->of(self::NAME)->lt()->get();

        $this->assertIsString($str);
        $this->assertStringContainsString(self::LT, $str);
    }

    public function testToNull(): void
    {
        $str = $this->str->toNull()->get();

        $this->assertNull($str);
    }

    public function testToNullWithString(): void
    {
        $str = $this->str->of(self::NAME)->toNull()->get();

        $this->assertIsString($str);
        $this->assertSame(self::NAME, $str);
    }

    public function testBefore(): void
    {
        $str = $this->str->of(self::DESCRIPTION)->before('-')->get();

        $this->assertIsString($str);
        $this->assertSame(self::BEFORE, $str);
    }

    public function testAfter(): void
    {
        $str = $this->str->of(self::DESCRIPTION)->after('-')->get();

        $this->assertIsString($str);
        $this->assertSame(self::AFTER, $str);
    }

    public function testBetween(): void
    {
        $str = $this->str->of(self::DESCRIPTION)->between(self::HELLO, self::HELPERS)->get();

        $this->assertIsString($str);
        $this->assertSame(self::BETWEEN, $str);
    }

    public function testCamel(): void
    {
        $str = $this->str->of(self::PREPEND . self::NAME)->replace('-', ' ')->camel()->get();

        $this->assertIsString($str);
        $this->assertSame(self::CAMEL, $str);
    }

    public function testPascal(): void
    {
        $str = $this->str->of(self::PREPEND . self::NAME)->replace('-', ' ')->pascal()->get();

        $this->assertIsString($str);
        $this->assertSame(self::PASCAL, $str);
    }

    public function testSnake(): void
    {
        $str = $this->str->of(self::PREPEND . self::NAME)->snake(['-'])->get();

        $this->assertIsString($str);
        $this->assertSame(self::SNAKE, $str);
    }

    public function testKebab(): void
    {
        $str = $this->str->of(self::PREPEND . self::NAME)->kebab(['-'])->get();

        $this->assertIsString($str);
        $this->assertSame(self::KEBAB, $str);
    }

    public function testHeadline(): void
    {
        $str = $this->str->of(self::PREPEND . self::NAME)->replace('-', ' ')->headline()->get();

        $this->assertIsString($str);
        $this->assertSame(self::HEADLINE, $str);
    }

    public function testLength(): void
    {
        $length = $this->str->of(self::NAME)->length();

        $this->assertIsInt($length);
        $this->assertSame(self::LENGHT, $length);
    }

    public function testLimit(): void
    {
        $str = $this->str->of(self::NAME)->limit(2)->get();

        $this->assertIsString($str);
        $this->assertSame(self::LIMIT, $str);
    }

    public function testLower(): void
    {
        $str = $this->str->of(self::UPPER)->lower()->get();

        $this->assertIsString($str);
        $this->assertSame(self::LOWER, $str);
    }

    public function testUpper(): void
    {
        $str = $this->str->of(self::LOWER)->upper()->get();

        $this->assertIsString($str);
        $this->assertSame(self::UPPER, $str);
    }

    #[DataProvider('maskProvider')]
    public function testMaskInit(string $value, string $char, int $length, string $return): void
    {
        $str = $this->str->of($value)->mask($char, $length)->get();

        $this->assertIsString($str);
        $this->assertSame($return, $str);
    }

    #[DataProvider('containsProvider')]
    public function testContains(array $words, bool $return): void
    {
        $validate = $this->str->of(self::DESCRIPTION)->contains($words);

        $this->assertIsBool($validate);
        $this->assertSame($return, $validate);
    }

    public function testSwap(): void
    {
        $str = $this->str->of(self::DESCRIPTION)->swap(self::SWAP_REPLACE)->get();

        $this->assertIsString($str);
        $this->assertSame(self::DESCRIPTION_SWAP, $str);
    }

    #[DataProvider('t3stProvider')]
    public function testTest(string $value, string $expr, bool $return): void
    {
        $validate = $this->str->of($value)->test($expr);

        $this->assertIsBool($validate);
        $this->assertSame($return, $validate);
    }

    #[DataProvider('trimProvider')]
    public function testTrim(string $desc, string $return, string $replace): void
    {
        $str = $this->str->of($desc)->trim($replace)->get();

        $this->assertIsString($str);
        $this->assertSame($return, $str);
    }

    #[DataProvider('concatProvider')]
    public function testConcat(string $desc, string $concat, string $return): void
    {
        $str = $this->str->of($desc)->spaces()->concat($concat)->get();

        $this->assertIsString($str);
        $this->assertSame($return, $str);
    }
}

<?php

declare(strict_types=1);

namespace Tests;

use Lion\Test\Test;
use LionHelpers\Str;
use Tests\Providers\StrProviderTrait;

class StrTest extends Test
{
    use StrProviderTrait;

    const NAME = 'root';
    const NAME_REPLACE = 'dev';
    const PREPEND = 'lion-';
    const LENGHT = 4;
    const LN = "\n";
    const LT = "\t";
    const DESCRIPTION = 'hello lion - lion/helpers';
    const DESCRIPTION_SWAP = 'hi dev - dev/test';
    const BEFORE = 'hello lion ';
    const AFTER = ' lion/helpers';
    const BETWEEN = ' lion - lion/';
    const HELLO = 'hello';
    const HELPERS = 'helpers';
    const CAMEL = 'lionRoot';
    const PASCAL = 'LionRoot';
    const SNAKE = 'lion_root';
    const KEBAB = 'lion-root';
    const HEADLINE = 'Lion Root';
    const LIMIT = 'ro';
    const LOWER = 'lion';
    const UPPER = 'LION';
    const SWAP_REPLACE = ['hello' => 'hi', 'helpers' => 'test', 'lion' => 'dev'];
    const TRIM = ' ' . self::DESCRIPTION . ' ';

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

    /**
     * @dataProvider maskProvider
     * */
    public function testMaskInit(string $value, string $char, int $length, string $return): void
    {
        $str = $this->str->of($value)->mask($char, $length)->get();

        $this->assertIsString($str);
        $this->assertSame($return, $str);
    }

    /**
     * @dataProvider containsProvider
     * */
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

    /**
     * @dataProvider t3stProvider
     * */
    public function testTest(string $value, string $expr, bool $return): void
    {
        $validate = $this->str->of($value)->test($expr);

        $this->assertIsBool($validate);
        $this->assertSame($return, $validate);
    }

    /**
     * @dataProvider trimProvider
     * */
    public function testTrim(string $desc, string $return, string $replace): void
    {
        $str = $this->str->of($desc)->trim($replace)->get();

        $this->assertIsString($str);
        $this->assertSame($return, $str);
    }

    /**
     * @dataProvider concatProvider
     * */
    public function testConcat(string $desc, string $concat, string $return): void
    {
        $str = $this->str->of($desc)->spaces()->concat($concat)->get();

        $this->assertIsString($str);
        $this->assertSame($return, $str);
    }
}

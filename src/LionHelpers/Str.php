<?php

declare(strict_types=1);

namespace Lion\Helpers;

use InvalidArgumentException;

/**
 * Modify and construct strings with different formats
 *
 * @package Lion\Helpers
 */
class Str
{
    /**
     * [Defines the minimum number of string characters]
     *
     * @const CHARACTER_LIMIT
     */
    private const int CHARACTER_LIMIT = 10;

    /**
     * [Modify and build arrays with different indexes or values]
     *
     * @var Arr $arr
     */
    private Arr $arr;

    /**
     * [Take the constructed text]
     *
     * @var null|string $word
     */
    private ?string $word = '';

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->arr = new Arr();
    }

    /**
     * Set the defined value as current value
     *
     * @param null|string $word [Take the constructed text]
     *
     * @return Str
     */
    public function of(?string $word): Str
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Gets the current value
     *
     * @return null|string
     */
    public function get(): ?string
    {
        $word = $this->word;

        $this->clean();

        return $word;
    }

    /**
     * Separates the string into parts with the defined characters
     *
     * @param non-empty-string $split [Character that separates the string into
     * parts]
     *
     * @return array<int, string>
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     */
    public function split(string $split): array
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        return explode($split, $this->word);
    }

    /**
     * Add spaces to current string
     *
     * @param int $spaces [Number of spaces that are nested in the current
     * string]
     *
     * @return Str
     */
    public function spaces(int $spaces = 1): Str
    {
        if (empty($this->word)) {
            $this->word = '';
        }

        for ($i = 0; $i < $spaces; $i++) {
            $this->word .= ' ';
        }

        return $this;
    }

    /**
     * Replaces the defined value with another value in the current string
     *
     * @param string $searchItem [Text searched to be replaced]
     * @param string $replaceItem [Text to replace string with another string]
     *
     * @return Str
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     */
    public function replace(string $searchItem, string $replaceItem): Str
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        $this->word = str_replace($searchItem, $replaceItem, $this->word);

        return $this;
    }

    /**
     * Adds the defined text to the beginning of the string
     *
     * @param string $prepend [String that is added to the start of the current
     * string]
     *
     * @return Str
     */
    public function prepend(string $prepend): Str
    {
        $this->word = $prepend . $this->word;

        return $this;
    }

    /**
     * Add a line break to the current string
     *
     * @return Str
     *
     * @infection-ignore-all
     */
    public function ln(): Str
    {
        if (empty($this->word)) {
            $this->word = '';
        }

        $this->word .=  "\n";

        return $this;
    }

    /**
     * Add a tab to the current string
     *
     * @return Str
     *
     * @infection-ignore-all
     */
    public function lt(): Str
    {
        if (empty($this->word)) {
            $this->word = '';
        }

        $this->word .=  "\t";

        return $this;
    }

    /**
     * Converts the current string to null or returns the same value
     * if the current value is different from null
     *
     * @return Str
     */
    public function toNull(): Str
    {
        if (empty($this->word)) {
            $this->word = null;
        }

        return $this;
    }

    /**
     * Gets the text contained before the defined word
     *
     * @param non-empty-string $delimiter [Defines the text that is returned
     * before other text]
     *
     * @return Str
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     *
     * @infection-ignore-all
     */
    public function before(string $delimiter): Str
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        /** @var string $first */
        $first = $this->arr
            ->of(explode($delimiter, $this->word))
            ->first();

        $this->word = $first;

        $this->toNull();

        return $this;
    }

    /**
     * Gets the text contained after the defined word
     *
     * @param non-empty-string $delimiter [Defines the text that is obtained
     * after other text]
     *
     * @return Str
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     *
     * @infection-ignore-all
     */
    public function after(string $delimiter): Str
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        /** @var string $last */
        $last = $this->arr
            ->of(explode($delimiter, $this->word))
            ->last();

        $this->word = $last;

        $this->toNull();

        return $this;
    }

    /**
     * Gets the text contained between the defined words
     *
     * @param non-empty-string $after [Defines the text that is obtained after
     * other text]
     * @param non-empty-string $before [Defines the text that is returned before
     * other text]
     *
     * @return Str
     */
    public function between(string $after, string $before): Str
    {
        $this->after($after);

        $this->before($before);

        return $this;
    }

    /**
     * Convert current string to Camel format
     *
     * @return Str
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     *
     * @infection-ignore-all
     */
    public function camel(): Str
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        $this->word = lcfirst(str_replace(' ', '', ucwords(strtolower($this->word))));

        return $this;
    }

    /**
     * Convert current string to Pascal format
     *
     * @return Str
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     *
     * @infection-ignore-all
     */
    public function pascal(): Str
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        $this->word = str_replace(" ", '', ucwords(strtolower($this->word)));

        return $this;
    }

    /**
     * Convert current string to Snake format
     *
     * @param array<int, string> $chars [List of characters to be removed]
     *
     * @return Str
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     *
     * @infection-ignore-all
     */
    public function snake(array $chars = []): Str
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        $this->word = $this->replaceChars('snake', $chars);

        return $this;
    }

    /**
     * Convert current string to Kebab format
     *
     * @param array<int, string> $chars [List of characters to be removed]
     *
     * @return Str
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     *
     * @infection-ignore-all
     */
    public function kebab(array $chars = []): Str
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        $this->word = $this->replaceChars('kebab', $chars);

        return $this;
    }

    /**
     * Adds formatting to the current string (initial letters in uppercase)
     *
     * @return Str
     */
    public function headline(): Str
    {
        $this->word = ucwords($this->replaceChars('all'));

        return $this;
    }

    /**
     * Gets the number of characters in the current string
     *
     * @return int
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     */
    public function length(): int
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        return strlen($this->word);
    }

    /**
     * Gets the first characters of the defined quantity of the current string
     * as the new current string
     *
     * @param int $limit [Limit of obtained characters]
     *
     * @return Str
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     */
    public function limit(int $limit = self::CHARACTER_LIMIT): Str
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        $newStr = '';

        $length = $this->length();

        $limit = min($limit, $length);

        for ($i = 0; $i < $limit; $i++) {
            $newStr .= $this->word[$i];
        }

        $this->word = $newStr;

        return $this;
    }

    /**
     * Convert current string to lowercase
     *
     * @return Str
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     */
    public function lower(): Str
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        $this->word = strtolower($this->word);

        return $this;
    }

    /**
     * Convert current string to uppercase
     *
     * @return Str
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     */
    public function upper(): Str
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        $this->word = strtoupper($this->word);

        return $this;
    }

    /**
     * Converts part of the string obtained in a range of characters
     * to specific characters
     *
     * @param string $char [Character with which characters are replaced]
     * @param int $ignore [Number of characters ignored]
     *
     * @return Str
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     *
     * @infection-ignore-all
     */
    public function mask(string $char = '*', int $ignore = self::CHARACTER_LIMIT): Str
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        $newStr = '';

        $size = $this->length();

        $ignoreSize = $size;

        if ($ignore < 0) {
            $ignoreSize = $ignoreSize - abs($ignore);
        }

        for ($i = 0; $i < $size; $i++) {
            if ($ignore > 0) {
                $newStr .= $i < $ignore ? $this->word[$i] : $char;
            } else {
                $newStr .= $i < $ignoreSize ? $char : $this->word[$i];
            }
        }

        $this->word = $newStr;

        return $this;
    }

    /**
     * Checks if the current string contains a certain number of defined words
     *
     * @param array<int, string> $words [List of words]
     *
     * @return bool
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     */
    public function contains(array $words): bool
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        foreach ($words as $word) {
            if (!(preg_match("/{$word}/i", $this->word))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Replaces words in the current string with new words
     *
     * @param array<string, string> $swaps [List of words]
     *
     * @return Str
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     *
     * @infection-ignore-all
     */
    public function swap(array $swaps): Str
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        $newStr = '';

        $i = 0;

        foreach ($swaps as $key => $swap) {
            if ($i === 0) {
                $i++;

                $newStr = str_replace($key, $swap, $this->word);
            } else {
                $newStr = str_replace($key, $swap, $newStr);
            }
        }

        $this->word = $newStr;

        return $this;
    }

    /**
     * Tests the current string with a regular expression
     *
     * @param string $test [Regular expression for validation]
     *
     * @return bool
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     */
    public function test(string $test): bool
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        return (bool) preg_match($test, $this->word);
    }

    /**
     * Clears spaces at the beginning and end of the current string
     *
     * @param string $replace [Remove a value from the string]
     *
     * @return Str
     *
     * @throws InvalidArgumentException [If there is no defined chain]
     */
    public function trim(string $replace = ''): Str
    {
        if (!is_string($this->word)) {
            throw new InvalidArgumentException('The defined string is not valid', 500);
        }

        $this->word = '' === $replace
            ? trim($this->word)
            : trim(str_replace($replace, '', $this->word));

        return $this;
    }

    /**
     * Concatenate strings to the current string
     *
     * @param string $word [Nest the text to the current string]
     *
     * @return Str
     */
    public function concat(string $word): Str
    {
        $this->word .= $word;

        return $this;
    }

    /**
     * Resets the class property to its original value
     *
     * @return void
     */
    private function clean(): void
    {
        $this->word = '';
    }

    /**
     * Replaces defined characters
     *
     * @param string $type [Defines the type of format (Pascal, Camel, Snake,
     * Kebab)]
     * @param array<int, string> $chars [List of characters to be removed]
     *
     * @return string
     *
     * @infection-ignore-all
     */
    private function replaceChars(string $type, array $chars = []): string
    {
        $itemDefaultChar = '';

        if ($type === 'kebab') {
            $itemDefaultChar = "_";

            $itemReplaceChar = "-";

            $chars[] = ' ';
        } elseif ($type === 'snake') {
            $itemDefaultChar = "-";

            $itemReplaceChar = "_";

            $chars[] = ' ';
        } else {
            $itemReplaceChar = " ";

            array_push($chars, '-', '_', '/', '\\', '*', '+', '=', '|', '[', ']', '(', ')');
        }

        $chars[] = $itemDefaultChar;

        foreach ($chars as $char) {
            $this->replace($char, $itemReplaceChar);
        }

        return strtolower((string) $this->word);
    }
}

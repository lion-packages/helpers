<?php

declare(strict_types=1);

namespace Lion\Helpers;

use Lion\Helpers\Arr;

/**
 * Modify and construct strings with different formats
 *
 * @property null|string $word [Take the constructed text]
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
    private const CHARACTER_LIMIT = 10;

    /**
     * [Take the constructed text]
     *
     * @var null|string $word
     */
    private ?string $word = '';

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
     * @param string $split [Character that separates the string into parts]
     *
     * @return array<string>
     */
    public function split(string $split): array
    {
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
        for ($i = 0; $i < $spaces; $i++) {
            $this->word .= " ";
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
     */
    public function replace(string $searchItem, string $replaceItem): Str
    {
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
     */
    public function ln(): Str
    {
        $this->word .=  "\n";

        return $this;
    }

    /**
     * Add a tab to the current string
     *
     * @return Str
     */
    public function lt(): Str
    {
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
        if (null === $this->word) {
            $this->word = null;

            return $this;
        }

        $this->word = empty(trim($this->word)) ? null : $this->word;

        return $this;
    }

    /**
     * Gets the text contained before the defined word
     *
     * @param string $delimiter [Defines the text that is returned before other
     * text]
     *
     * @return Str
     */
    public function before(string $delimiter): Str
    {
        $this->word = (new Arr())->of(explode($delimiter, $this->word))->first();

        $this->toNull();

        return $this;
    }

    /**
     * Gets the text contained after the defined word
     *
     * @param string $delimiter [Defines the text that is obtained after other
     * text]
     *
     * @return Str
     */
    public function after(string $delimiter): Str
    {
        $this->word = (new Arr())->of(explode($delimiter, $this->word))->last();

        $this->toNull();

        return $this;
    }

    /**
     * Gets the text contained between the defined words
     *
     * @param string $after [Defines the text that is obtained after
     * other text]
     * @param string $before [Defines the text that is returned before other
     * text]
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
     */
    public function camel(): Str
    {
        $this->word = lcfirst(str_replace(" ", '', ucwords(strtolower($this->word))));

        return $this;
    }

    /**
     * Convert current string to Pascal format
     *
     * @return Str
     */
    public function pascal(): Str
    {
        $this->word = str_replace(" ", '', ucwords(strtolower($this->word)));

        return $this;
    }

    /**
     * Convert current string to Snake format
     *
     * @param array<string> $chars [List of characters to be removed]
     *
     * @return Str
     */
    public function snake(array $chars = []): Str
    {
        $this->word = $this->replaceChars('snake', $chars);

        return $this;
    }

    /**
     * Convert current string to Kebab format
     *
     * @param array<string> $chars [List of characters to be removed]
     *
     * @return Str
     */
    public function kebab(array $chars = []): Str
    {
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
     */
    public function length(): int
    {
        return strlen($this->word);
    }

    /**
     * Gets the first characters of the defined quantity of the current string
     * as the new current string
     *
     * @param int $limit [Limit of obtained characters]
     *
     * @return Str
     */
    public function limit(int $limit = self::CHARACTER_LIMIT): Str
    {
        $newStr = '';

        $length = $this->length();

        $limit = $limit > $length ? $length : $limit;

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
     */
    public function lower(): Str
    {
        $this->word = strtolower($this->word);

        return $this;
    }

    /**
     * Convert current string to uppercase
     *
     * @return Str
     */
    public function upper(): Str
    {
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
     */
    public function mask(string $char = '*', int $ignore = self::CHARACTER_LIMIT): Str
    {
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
     * @param array<string> $words [List of words]
     *
     * @return bool
     */
    public function contains(array $words): bool
    {
        foreach ($words as $word) {
            if (!((bool) preg_match("/{$word}/i", $this->word))) {
                return false;

                break;
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
     */
    public function swap(array $swaps): Str
    {
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
     */
    public function test(string $test): bool
    {
        return (bool) preg_match($test, $this->word);
    }

    /**
     * Clears spaces at the beginning and end of the current string
     *
     * @param string $replace [Remove a value from the string]
     *
     * @return Str
     */
    public function trim(string $replace = ''): Str
    {
        $this->word = '' === $replace ? trim($this->word) : trim(str_replace($replace, '', $this->word));

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
     * @param array $chars [List of characters to be removed]
     *
     * @return string
     */
    private function replaceChars(string $type, array $chars = []): string
    {
        $itemDefaultChar = '';

        $itemReplaceChar = '';

        if ($type === 'kebab') {
            $itemDefaultChar = "_";

            $itemReplaceChar = "-";

            array_push($chars, ' ');
        } elseif ($type === 'snake') {
            $itemDefaultChar = "-";

            $itemReplaceChar = "_";

            array_push($chars, ' ');
        } else {
            $itemReplaceChar = " ";

            array_push($chars, '-', '_', '/', '\\', '*', '+', '=', '|', '[', ']', '(', ')');
        }

        array_push($chars, $itemDefaultChar);

        foreach ($chars as $char) {
            $this->replace($char, $itemReplaceChar);
        }

        return strtolower($this->word);
    }
}

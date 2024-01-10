<?php

declare(strict_types=1);

namespace Lion\Helpers;

use Lion\Helpers\Arr;

class Str
{
	private ?string $word = '';

	/**
	 * Resets the class property to its original value
	 * */
	private function clean(): void
	{
		$this->word = '';
	}

	/**
	 * Replaces defined characters
	 * */
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

		if (count($chars) > 0) {
			foreach ($chars as $char) {
				$this->replace($char, $itemReplaceChar);
			}
		}

		return strtolower($this->word);
	}

	/**
	 * Gets the current value
	 * */
	public function get(): ?string
	{
		$word = $this->word;
		$this->clean();

		return $word;
	}

	/**
	 * Set the defined value as current value
	 * */
	public function of(?string $word): Str
	{
		$this->word = $word;

		return $this;
	}

	/**
	 * Separates the string into parts with the defined characters
	 * */
	public function split(string $split): array
	{
		return explode($split, $this->word);
	}

	/**
	 * Add spaces to current string
	 * */
	public function spaces(int $spaces = 1): Str
	{
		for ($i = 0; $i < $spaces; $i++) {
			$this->word .= " ";
		}

		return $this;
	}

	/**
	 * Replaces the defined value with another value in the current string
	 * */
	public function replace($searchItem, $replaceItem): Str
	{
		$this->word = str_replace($searchItem, $replaceItem, $this->word);

		return $this;
	}

	/**
	 * Adds the defined text to the beginning of the string
	 * */
	public function prepend(string $prepend): Str
	{
		$this->word = $prepend . $this->word;

		return $this;
	}

	/**
	 * Add a line break to the current string
	 * */
	public function ln(): Str
	{
		$this->word .=  "\n";

		return $this;
	}

	/**
	 * Add a tab to the current string
	 * */
	public function lt(): Str
	{
		$this->word .=  "\t";

		return $this;
	}

	/**
	 * Converts the current string to null or returns the same value
	 * if the current value is different from null
	 * */
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
	 * */
	public function before(string $delimiter): Str
	{
		$this->word = (new Arr())->of(explode($delimiter, $this->word))->first();
		$this->toNull();

		return $this;
	}

	/**
	 * Gets the text contained after the defined word
	 * */
	public function after(string $delimiter): Str
	{
		$this->word = (new Arr())->of(explode($delimiter, $this->word))->last();
		$this->toNull();

		return $this;
	}

	/**
	 * Gets the text contained between the defined words
	 * */
	public function between($firstDelimiter, $secondDelimiter): Str
	{
		$this->after($firstDelimiter);
		$this->before($secondDelimiter);

		return $this;
	}

	/**
	 * Convert current string to Camel format
	 * */
	public function camel(): Str
	{
		$this->word = lcfirst(str_replace(" ", '', ucwords(strtolower($this->word))));

		return $this;
	}

	/**
	 * Convert current string to Pascal format
	 * */
	public function pascal(): Str
	{
		$this->word = str_replace(" ", '', ucwords(strtolower($this->word)));

		return $this;
	}

	/**
	 * Convert current string to Snake format
	 * */
	public function snake(array $chars = []): Str
	{
		$this->word = $this->replaceChars('snake', $chars);

		return $this;
	}

	/**
	 * Convert current string to Kebab format
	 * */
	public function kebab(array $chars = []): Str
	{
		$this->word = $this->replaceChars('kebab', $chars);

		return $this;
	}

	/**
	 * Adds formatting to the current string (initial letters in uppercase)
	 * */
	public function headline(): Str
	{
		$this->word = ucwords($this->replaceChars('all'));

		return $this;
	}

	/**
	 * Gets the number of characters in the current string
	 * */
	public function length(): int
	{
		return strlen($this->word);
	}

	/**
	 * gets the first characters of the defined quantity of the current
	 * string as new current string
	 * */
	public function limit(int $limit = 10): Str
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
	 * */
	public function lower(): Str
	{
		$this->word = strtolower($this->word);

		return $this;
	}

	/**
	 * Convert current string to uppercase
	 * */
	public function upper(): Str
	{
		$this->word = strtoupper($this->word);

		return $this;
	}

	/**
	 * Converts part of the string obtained in a range of characters
	 * to specific characters
	 * */
	public function mask(string $char, int $ignore): Str
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
	 * */
	public function contains(array $words): bool
	{
		foreach ($words as $key => $word) {
			if (!preg_match("/{$word}/i", $this->word)) {
				return false;
				break;
			}
		}

		return true;
	}

	/**
	 * Replaces words in the current string with new words
	 * */
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
	 * */
	public function test(string $test): bool
	{
		return (bool) preg_match($test, $this->word);
	}

	/**
	 * Clears spaces at the beginning and end of the current string
	 * */
	public function trim(string $trim = ''): Str
	{
		$this->word = '' === $trim ? trim($this->word) : trim(str_replace($trim, '', $this->word));

		return $this;
	}

	/**
	 * Concatenate strings to the current string
	 * */
	public function concat(string $word): Str
	{
		$this->word .= $word;

		return $this;
	}
}

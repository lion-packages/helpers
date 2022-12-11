<?php

namespace LionHelpers;

use LionHelpers\Arr;

class Str {

	private static ?Str $str = null;
	private static ?string $word;

	public function __construct() {

	}

	private static function replaceChars(string $type, array $chars = []): string {
		$item_default_chart = "";
		$item_replace_chart = "";

		if ($type === 'kebab') {
			$item_default_chart = "_";
			$item_replace_chart = "-";
			array_push($chars, ' ');
		} elseif ($type === 'snake') {
			$item_default_chart = "-";
			$item_replace_chart = "_";
			array_push($chars, ' ');
		} else {
			$item_replace_chart = " ";
			array_push($chars, '-', '_', '/', '\\', '*', '+', '=', '|', '[', ']', '(', ')');
		}

		array_push($chars, $item_default_chart);

		if (count($chars) > 0) {
			foreach ($chars as $key => $char) {
				self::replace($char, $item_replace_chart);
			}
		}

		return strtolower(self::$word);
	}

	public static function get(): string {
		return self::$word;
	}

	public static function replace($search_item, $replace_item): Str {
		self::$word = str_replace($search_item, $replace_item, self::$word);
		return self::$str;
	}

	public static function of(mixed $word): Str {
		if (self::$str === null) {
			self::$str = new Str();
		}

		self::$word = $word;
		return self::$str;
	}

	public static function prepend(string $prepend): string {
		return $prepend . self::$word;
	}

	public static function ln(): string {
		return self::$word . "\n";
	}

	public static function toString(): string {
		return self::$word === null ? "" : self::$word;
	}

	public static function toNull(): ?string {
		if (self::$word === null) {
			return null;
		}

		return empty(trim(self::$word)) ? null : self::$word;
	}

	public static function before(string $delimiter): string {
		self::$word = Arr::first(explode($delimiter, self::$word));
		$first = self::toNull();
		return $first === null ? self::$word : $first;
	}

	public static function after(string $delimiter): string {
		self::$word = Arr::last(explode($delimiter, self::$word));
		$last = self::toNull();
		return $last === null ? self::$word : $last;
	}

	public static function between($first_delimiter, $second_delimiter): string {
		self::$word = self::after($first_delimiter);
		return self::before($second_delimiter);
	}

	public static function camel(): mixed {
		return lcfirst(str_replace(" ", "", ucwords(self::$word)));
	}

	public static function pascal(): string {
		return str_replace(" ", "", ucwords(self::$word));
	}

	public static function snake(array $chars = []): string {
		return self::replaceChars('snake', $chars);
	}

	public static function kebab(array $chars = []): string {
		return self::replaceChars('kebab', $chars);
	}

	public static function headline(): string {
		return ucwords(self::replaceChars('all'));
	}

	public static function length(): int {
		return strlen(self::$word);
	}

	public static function limit(int $limit = 10): string {
		$new_str = "";
		$length = self::length();
		$limit = $limit > $length ? $length : $limit;

		for ($i = 0; $i < $limit; $i++) {
			$new_str .= self::$word[$i];
		}

		return $new_str;
	}

	public static function lower(): string {
		return strtolower(self::$word);
	}

	public static function upper(): string {
		return strtoupper(self::$word);
	}

	public static function mask(string $char, int $ignore): string {
		$new_str = "";
		$size = self::length(self::$word);
		$ignore_size = $size;
		if ($ignore < 0) $ignore_size = $ignore_size - abs($ignore);

		for ($i = 0; $i < $size; $i++) {
			if ($ignore > 0) {
				$new_str .= $i < $ignore ? self::$word[$i] : $char;
			} else {
				$new_str .= $i < $ignore_size ? $char : self::$word[$i];
			}
		}

		return $new_str;
	}

	public static function contains(array $words): bool {
		foreach ($words as $key => $word) {
			if(!preg_match("/{$word}/i", self::$word)) {
				return false;
				break;
			}
		}

		return true;
	}

	public static function swap(array $swaps): string {
		$new_str = "";
		$i = 0;

		foreach ($swaps as $key => $swap) {
			if ($i === 0) {
				$i++;
				$new_str = str_replace($key, $swap, self::$word);
			} else {
				$new_str = str_replace($key, $swap, $new_str);
			}
		}

		return $new_str;
	}

	public static function test(string $test): bool {
		return preg_match($test, self::$word);
	}

	public static function trim(string $trim = ""): string {
		return $trim === "" ? trim(self::$word) : trim(str_replace($trim, "", self::$word));
	}

	public static function concat(string $word): Str {
		self::$word .= $word;
		return self::$str;
	}

}
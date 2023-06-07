<?php

namespace LionHelpers;

use LionHelpers\Arr;

class Str {

	private static ?Str $str = null;
	private static ?string $word;

	public function __construct() {

	}

	private static function clean(): void {
		self::$word = '';
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
		$new_str = self::$word;
		self::clean();
		return $new_str;
	}

	public static function of(mixed $word): Str {
		self::$str = new Str();
		self::$word = $word;
		return self::$str;
	}

	// ---------------------------------------------------------------------------------------------

	public static function spaces(int $spaces = 1): Str {
		for ($i = 0; $i < $spaces; $i++) {
			self::$word .= " ";
		}

		return self::$str;
	}

	public static function replace($search_item, $replace_item): Str {
		self::$word = str_replace($search_item, $replace_item, self::$word);
		return self::$str;
	}

	public static function prepend(string $prepend): Str {
		self::$word = $prepend . self::$word;
		return self::$str;
	}

	public static function ln(): Str {
		self::$word .=  "\n";
		return self::$str;
	}

	public static function lt(): Str {
		self::$word .=  "\t";
		return self::$str;
	}

	public static function toString(): Str {
		self::$word = self::$word === null ? "" : self::$word;
		return self::$str;
	}

	public static function toNull(): ?string {
		if (self::$word === null) {
			return null;
		}

		return empty(trim(self::$word)) ? null : self::$word;
	}

	public static function before(string $delimiter): string {
		self::$word = Arr::of(explode($delimiter, self::$word))->first();
		$first = self::toNull();
		return $first === null ? self::$word : $first;
	}

	public static function after(string $delimiter): string {
		self::$word = Arr::of(explode($delimiter, self::$word))->last();
		$last = self::toNull();
		return $last === null ? self::$word : $last;
	}

	public static function between($first_delimiter, $second_delimiter): string {
		self::$word = self::after($first_delimiter);
		return self::before($second_delimiter);
	}

	public static function camel(): Str {
		self::$word = lcfirst(str_replace(" ", "", ucwords(strtolower(self::$word))));
		return self::$str;
	}

	public static function pascal(): Str {
		self::$word = str_replace(" ", "", ucwords(strtolower(self::$word)));
		return self::$str;
	}

	public static function snake(array $chars = []): Str {
		self::$word = self::replaceChars('snake', $chars);
		return self::$str;
	}

	public static function kebab(array $chars = []): Str {
		self::$word = self::replaceChars('kebab', $chars);
		return self::$str;
	}

	public static function headline(): Str {
		self::$word = ucwords(self::replaceChars('all'));
		return self::$str;
	}

	public static function length(): int {
		return strlen(self::$word);
	}

	public static function limit(int $limit = 10): Str {
		$new_str = "";
		$length = self::length();
		$limit = $limit > $length ? $length : $limit;

		for ($i = 0; $i < $limit; $i++) {
			$new_str .= self::$word[$i];
		}

		self::$word = $new_str;
		return self::$str;
	}

	public static function lower(): Str {
		self::$word = strtolower(self::$word);
		return self::$str;
	}

	public static function upper(): Str {
		self::$word = strtoupper(self::$word);
		return self::$str;
	}

	public static function mask(string $char, int $ignore): Str {
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

		self::$word = $new_str;
		return self::$str;
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

	public static function swap(array $swaps): Str {
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

		self::$word = $new_str;
		return self::$str;
	}

	public static function test(string $test): bool {
		return preg_match($test, self::$word);
	}

	public static function trim(string $trim = ""): Str {
		self::$word = $trim === "" ? trim(self::$word) : trim(str_replace($trim, "", self::$word));
		return self::$str;
	}

	public static function concat(string $word): Str {
		self::$word .= $word;
		return self::$str;
	}

}
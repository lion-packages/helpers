<?php

namespace LionHelpers;

use LionHelpers\Arr;

class Str {

	private function __construct() {

	}

	private static function replaceChars(string $type, string $str, array $chars = []): string {
		$item_default_chart = "";
		$item_replace_chart = "";

		if ($type === 'kebab') {
			$item_default_chart = "_";
			$item_replace_chart = "-";
		} elseif ($type === 'snake') {
			$item_default_chart = "-";
			$item_replace_chart = "_";
		} else {
			$item_replace_chart = " ";
			array_push($chars, '-', '_', '/', '\\');
		}

		array_push($chars, $item_default_chart);

		if (count($chars) > 0) {
			foreach ($chars as $key => $char) {
				$str = str_replace($char, $item_replace_chart, $str);
			}
		}

		return strtolower($str);
	}

	public static function toNull(?string $str): ?string {
		if ($str === null) {
			return null;
		}

		return empty(trim($str)) ? null : $str;
	}

	public static function before(string $str, string $delimiter): string {
		$separate = explode($delimiter, $str);
		$first = self::toNull(Arr::first($separate));
		return $first === null ? trim($str) : trim($first);
	}

	public static function after(string $str, string $delimiter): mixed {
		$separate = explode($delimiter, $str);
		$last = self::toNull(Arr::last($separate));
		return $last === null ? trim($str) : trim($last);
	}

	public static function between(string $str, $first_delimiter, $second_delimiter) {
		return trim(self::before(self::after($str, $first_delimiter), $second_delimiter));
	}

	public static function camel(string $str): string {
		$str = str_replace("_", " ", str_replace("-", " ", $str));
		$str = Arr::join(explode(" ", $str), "");
		return trim(lcfirst($str));
	}

	public static function pascal(string $str): string {
		$str = ucwords(str_replace("_", " ", str_replace("-", " ", $str)));
		return trim(Arr::join(explode(" ", $str), ""));
	}

	public static function snake(string $str, array $chars = []): string {
		return trim(self::replaceChars('snake', $str, $chars));
	}

	public static function kebab(string $str, array $chars = []): string {
		return trim(self::replaceChars('kebab', $str, $chars));
	}

	public static function headline(string $str): string {
		return trim(ucwords(self::replaceChars('all', $str)));
	}

	public static function length(string $str): int {
		return strlen($str);
	}

	public static function limit(string $str, int $limit = 10): string {
		$new_str = "";

		for ($i = 0; $i < $limit; $i++) {
			$new_str .= $str[$i];
		}

		return $new_str;
	}

	public static function lower(string $str): string {
		return trim(strtolower($str));
	}

	public static function upper(string $str): string {
		return trim(strtoupper($str));
	}

	public static function mask(string $str, string $char, int $ignore): string {
		$new_str = "";
		$size = self::length($str);
		$ignore_size = $size;
		if ($ignore < 0) $ignore_size = $ignore_size - abs($ignore);

		for ($i = 0; $i < $size; $i++) {
			if ($ignore > 0) {
				$new_str .= $i < $ignore ? $str[$i] : $char;
			} else {
				$new_str .= $i < $ignore_size ? $char : $str[$i];
			}
		}

		return $new_str;
	}

	public static function contains(string $str, array $words): bool {
		foreach ($words as $key => $word) {
			if(!preg_match("/{$word}/i", $str)) {
				return false;
				break;
			}
		}

		return true;
	}

}
<?php

namespace LionHelpers;

use LionHelpers\Str;

class Arr {

	private static ?Arr $arr = null;
	private static ?array $items = [];

	public function __construct() {

	}

	private static function clean(): void {
		self::$items = [];
	}

	public static function keys(): Arr {
		self::$items = array_keys(self::$items);
		return self::$arr;
	}

	public static function values(): Arr {
		self::$items = array_values(self::$items);
		return self::$arr;
	}

	public static function get(): array {
		return self::$items;
	}

	public static function push(mixed $value, int|string $key = ''): Arr {
		if ($key === '') {
			self::$items[] = $value;
		} else {
			self::$items[$key] = $value;
		}

		return self::$arr;
	}

	public static function of(array $items = []): Arr {
		if (self::$arr === null) {
			self::$arr = new Arr();
		}

		self::$items = $items;
		return self::$arr;
	}

	public static function length(): int {
		return count(self::$items);
	}

	public static function join(string $delimiter, string $str = ""): string {
		$new_text = "";
		$size = self::length(self::$items) - 1;

		foreach (self::$items as $key => $item) {
			if ($key === 0) {
				$new_text .= $item;
			} elseif ($key === $size) {
				$new_text .= $str != "" ? ($str . $item) : ($delimiter . $item);
			} else {
				$new_text .= ($delimiter . $item);
			}
		}

		return $new_text;
	}

	public static function keyBy(string $column): array {
		$new_items = [];

		foreach (self::$items as $key => $item) {
			if (gettype($item) === 'object') {
				$new_items[$item->{"{$column}"}] = $item;
			} else {
				$new_items[$item[$column]] = $item;
			}
		}

		self::clean();
		return $new_items;
	}

	public static function tree(string $column): array {
		$new_items = [];

		foreach (self::$items as $key => $item) {
			if (gettype($item) === 'object') {
				if (!isset($new_items[$item->{"{$column}"}])) {
					$new_items[$item->{"{$column}"}] = [$item];
				} else {
					array_push($new_items[$item->{"{$column}"}], $item);
				}
			} else {
				if (!isset($new_items[$item[$column]])) {
					$new_items[$item[$column]] = [$item];
				} else {
					array_push($new_items[$item[$column]], $item);
				}
			}
		}

		self::clean();
		return $new_items;
	}

	public static function prepend(string $item, string $key = ""): array {
		$new_arr = $key === "" ? [$item, ...self::$items] : [$key => $item, ...self::$items];
		self::clean();
		return $new_arr;
	}

	public static  function random(int $cont = 1): mixed {
		$size = self::length();

		if ($cont > $size) {
			return (object) ['status' => 'error', 'message' => "element size exceeds array size"];
		}

		$all_items = [];
		$all_size = $size - 1;
		$get_random_item = fn(array $items, $all_size) => $items[rand(0, $all_size)];
		$search_item = fn(array $all_items, mixed $item) => in_array($item, $all_items);

		if ($cont > 1) {
			$iterate = 0;

			do {
				$item = $get_random_item(self::$items, $all_size);

				if (!$search_item($all_items, $item)) {
					$all_items[] = $item;
					$iterate++;
				}
			} while ($iterate < $cont);

			self::clean();
			return $all_items;
		}

		$new_arr = $get_random_item(self::$items, $all_size);
		self::clean();
		return $new_arr;
	}

	public static  function sort(int $type): Arr {
		sort(self::$items, $type);
		return self::$arr;
	}

	public static  function where(\Closure $callback): array {
		$new_items = [];

		foreach (self::$items as $key => $item) {
			if ($callback($item, $key)) {
				$new_items[$key] = $item;
			}
		}

		self::clean();
		return $new_items;
	}

	public static function whereNotNull(): array {
		$new_items = [];

		foreach (self::$items as $key => $item) {
			if (in_array(gettype($item), ['array', 'object', 'closure'])) {
				$new_items[$key] = $item;
			} else {
				if (Str::of($item)->toNull() != null) {
					$new_items[$key] = $item;
				}
			}
		}

		self::clean();
		return $new_items;
	}

	public static function first(): mixed {
		$new_arr = self::$items[0];
		self::clean();
		return $new_arr;
	}

	public static function last(): mixed {
		$new_arr = self::$items[self::length(self::$items) - 1];
		self::clean();
		return $new_arr;
	}

	public static function wrap(mixed $value): array {
		return $value === null ? [] : [$value];
	}

}
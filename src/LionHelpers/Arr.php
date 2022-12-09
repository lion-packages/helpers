<?php

namespace LionHelpers;

use LionHelpers\Str;

class Arr {

	private function __construct() {

	}

	public static function join(array $items, string $delimiter, string $str = ""): string {
		$new_text = "";
		$size = count($items) - 1;

		foreach ($items as $key => $item) {
			if ($key === 0) {
				$new_text .= $item;
			} elseif ($key === $size) {
				$new_text .= $str != "" ? "{$str}{$item}" : "{$delimiter}{$item}";
			} else {
				$new_text .= "{$delimiter}{$item}";
			}
		}

		return $new_text;
	}

	public static function keyBy(array $items, string $column): array {
		$new_items = [];

		foreach ($items as $key => $item) {
			$new_items[$item->{"{$column}"}] = $item;
		}

		return $new_items;
	}

	public static function tree(array $items, string $column): array {
		$new_items = [];

		foreach ($items as $key => $item) {
			if (!isset($new_items[$item->{"{$column}"}])) {
				$new_items[$item->{"{$column}"}] = [$item];
			} else {
				array_push($new_items[$item->{"{$column}"}], $item);
			}
		}

		return $new_items;
	}

	public static function prepend(array $items, string $item, string $key = ""): array {
		return $key === "" ? [$item, ...$items] : [$key => $item, ...$items];
	}

	public static  function random(array $items, int $cont = 1): mixed {
		$size = count($items);

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
				$item = $get_random_item($items, $all_size);

				if (!$search_item($all_items, $item)) {
					$all_items[] = $item;
					$iterate++;
				}
			} while ($iterate < $cont);

			return $all_items;
		}

		return $get_random_item($items, $all_size);
	}

	public static  function sort(array $items): array {
		asort($items);
		return $items;
	}

	public static  function where(array $items, \Closure $callback) {
		$new_items = [];

		foreach ($items as $key => $item) {
			if ($callback($item, $key)) {
				$new_items[$key] = $item;
			}
		}

		return $new_items;
	}

	public static function whereNotNull(array $items): array {
		$new_items = [];

		foreach ($items as $key => $item) {
			if (Str::toNull($item) != null) {
				$new_items[$key] = $item;
			}
		}

		return $new_items;
	}

	public static function first(array $items): mixed {
		return $items[0];
	}

	public static function last(array $items): mixed {
		return $items[count($items) - 1];
	}

	public static function wrap(mixed $value): array {
		return $value === null ? [] : [$value];
	}

}
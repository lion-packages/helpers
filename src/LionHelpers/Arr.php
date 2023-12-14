<?php

declare(strict_types=1);

namespace LionHelpers;

use Closure;
use LionHelpers\Str;

class Arr
{
	private array|object $items = [];

    private function clean(): void
    {
        $this->items = [];
    }

    public function toObject(): Arr
    {
        $this->items = (object) $this->items;

        return $this;
    }

	public function keys(): Arr
    {
		$this->items = array_keys($this->items);

        return $this;
	}

	public function values(): Arr
    {
		$this->items = array_values($this->items);

        return $this;
	}

	public function get(): array|object
    {
		return $this->items;
	}

	public function push(mixed $value, int|string $key = ''): Arr
    {
		if ('' === $key) {
			$this->items[] = $value;
		} else {
			$this->items[$key] = $value;
		}


        return $this;
	}

	public function of(array $items): Arr
    {
		$this->items = $items;

        return $this;
	}

	public function length(): int
    {
		return count($this->items);
	}

	public function join(string $delimiter, string $str = ''): string
    {
		$newText = '';
		$size = self::length($this->items) - 1;

		foreach ($this->items as $key => $item) {
			if ($key === 0) {
				$newText .= $item;
			} elseif ($key === $size) {
				$newText .= $str != '' ? ($str . $item) : ($delimiter . $item);
			} else {
				$newText .= ($delimiter . $item);
			}
		}

		return $newText;
	}

	public function keyBy(string $column): Arr
    {
		$newItems = [];

		foreach ($this->items as $key => $item) {
			if ('object' === gettype($item)) {
				$newItems[$item->{"{$column}"}] = $item;
			} else {
				$newItems[$item[$column]] = $item;
			}
		}

		$this->items = $newItems;

        return $this;
	}

	public function tree(string $column): Arr
    {
		$newItems = [];

		foreach ($this->items as $key => $item) {
			if ('object' === gettype($item)) {
				if (!isset($newItems[$item->{"{$column}"}])) {
					$newItems[$item->{"{$column}"}] = [$item];
				} else {
					array_push($newItems[$item->{"{$column}"}], $item);
				}
			} else {
				if (!isset($newItems[$item[$column]])) {
					$newItems[$item[$column]] = [$item];
				} else {
					array_push($newItems[$item[$column]], $item);
				}
			}
		}

		$this->items = $newItems;

        return $this;
	}

	public function prepend(string $item, string $key = ''): Arr
    {
		$this->items = '' === $key ? [$item, ...$this->items] : [$key => $item, ...$this->items];

		return $this;
	}

	public function random(int $cont = 1): Arr
    {
		$size = $this->length();

		if ($cont > $size) {
			return (object) ['status' => 'error', 'message' => 'element size exceeds array size'];
		}

		$allItems = [];
		$allSize = $size - 1;
		$getRandomItem = fn(array $items, $allSize) => $items[rand(0, $allSize)];
		$searchItem = fn(array $allItems, mixed $item) => in_array($item, $allItems);

		if ($cont > 1) {
			$iterate = 0;

			do {
				$item = $getRandomItem($this->items, $allSize);

				if (!$searchItem($allItems, $item)) {
					$allItems[] = $item;
					$iterate++;
				}
			} while ($iterate < $cont);

			$this->items = $allItems;

			return $this;
		}

		$this->items = $getRandomItem($this->items, $allSize);

		return $this;
	}

	public function sort(int $type): Arr
    {
		sort($this->items, $type);

        return $this;
	}

	public function where(Closure $callback): Arr
    {
		$newItems = [];

		foreach ($this->items as $key => $item) {
			if ($callback($item, $key)) {
				$newItems[$key] = $item;
			}
		}

        $this->items = $newItems;

		return $this;
	}

	public function whereNotNull(): Arr
    {
		$newItems = [];

		foreach ($this->items as $key => $item) {
			if (in_array(gettype($item), ['array', 'object', 'closure'])) {
				$newItems[$key] = $item;
			} else {
				if (Str::of($item)->toNull() != null) {
					$newItems[$key] = $item;
				}
			}
		}

		$this->items = $newItems;

		return $this;
	}

	public function first(): mixed
    {
		$value = $this->items[0];
		$this->clean();

		return $value;
	}

	public function last(): mixed
    {
		$new_arr = $this->items[self::length($this->items) - 1];
		$this->clean();

		return $new_arr;
	}

	public function wrap(mixed $value): Arr
    {
		$this->items = $value === null ? [] : [$value];

        return $this;
	}
}

<?php

declare(strict_types=1);

namespace Lion\Helpers;

use Closure;
use InvalidArgumentException;
use Lion\Helpers\Str;

class Arr
{
    private array|object $items = [];

    /**
     * Resets the class property to its original value
     * */
    private function clean(): void
    {
        $this->items = [];
    }

    /**
     * Convert object to current array
     * */
    public function toObject(): Arr
    {
        $this->items = (object) $this->items;

        return $this;
    }

    /**
     * Get the indexes of the current array
     * */
    public function keys(): Arr
    {
        $this->items = array_keys($this->items);

        return $this;
    }

    /**
     * Get the values of the current array
     * */
    public function values(): Arr
    {
        $this->items = array_values($this->items);

        return $this;
    }

    /**
     * Gets the current value
     * */
    public function get(): array|object
    {
        $items = $this->items;
        $this->clean();

        return $items;
    }

    /**
     * Add an element to the current array
     * */
    public function push(mixed $value, int|string $key = ''): Arr
    {
        if ('' === $key) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }

        return $this;
    }

    /**
     * Set the defined value as current value
     * */
    public function of(array $items): Arr
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Gets the number of characters in the current string
     * */
    public function length(): int
    {
        return count($this->items);
    }

    /**
     * Joins the values of the current array in string format using the defined delimiter
     * */
    public function join(string $separator = ', ', ?string $lastSeparator = null): string
    {
        $items = $this->items;
        $this->clean();

        if (null === $lastSeparator) {
            return implode($separator, $items);
        }

        $lastElement = array_pop($items);

        return implode($separator, $items) . "{$lastSeparator}{$lastElement}";
    }

    /**
     * Uses the value of a column of a matrix as the key of the same matrix
     * */
    public function keyBy(string $column): Arr
    {
        $newItems = [];

        foreach ($this->items as $item) {
            if ('object' === gettype($item)) {
                $newItems[$item->{"{$column}"}] = $item;
            } else {
                $newItems[$item[$column]] = $item;
            }
        }

        $this->items = $newItems;

        return $this;
    }

    /**
     * It uses the value of a column of an array as a key by creating a new
     * array and adding all arrays that share the same column value
     * */
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

    /**
     * Adds a defined value to the start of the current array
     * */
    public function prepend(string $item, string $key = ''): Arr
    {
        $this->items = '' === $key ? [$item, ...$this->items] : [$key => $item, ...$this->items];

        return $this;
    }

    /**
     * Select a number of random elements from an array
     * */
    public function random(int $limit = 1): Arr
    {
        $size = $this->length();

        if ($limit > $size) {
            throw new InvalidArgumentException('element size exceeds array size');
        }

        $randomIdxs = [];

        do {
            $randomIdx = array_rand($this->items);

            if (!in_array($randomIdx, $randomIdxs, true)) {
                $randomIdxs[] = $randomIdx;
            }

            if (count($randomIdxs) === $limit) {
                break;
            }
        } while (true);

        $randomElements = [];

        foreach ($randomIdxs as $key) {
            $randomElements[] = $this->items[$key];
        }

        $this->items = $randomElements;

        return $this;
    }

    /**
     * Gets a new array of elements based on its condition
     * */
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

    /**
     * Gets a new array of elements where the values are neither null nor empty
     * */
    public function whereNotEmpty(): Arr
    {
        $str = new Str();
        $newItems = [];

        foreach ($this->items as $key => $item) {
            if (in_array(gettype($item), ['array', 'object', 'closure'])) {
                $newItems[$key] = $item;
            } else {
                if (!empty($str->of($item)->toNull()->get())) {
                    $newItems[$key] = $item;
                }
            }
        }

        $this->items = $newItems;

        return $this;
    }

    /**
     * Gets the first element of the current array
     * */
    public function first(): mixed
    {
        $value = reset($this->items);
        $this->clean();

        return $value;
    }

    /**
     * Gets the last element of the current array
     * */
    public function last(): mixed
    {
        $new_arr = end($this->items);
        $this->clean();

        return $new_arr;
    }

    /**
     * Create a new array with a value inside
     * */
    public function wrap(mixed $value = null): Arr
    {
        $this->items = empty($value) ? [] : [$value];

        return $this;
    }
}

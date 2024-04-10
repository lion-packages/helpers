<?php

declare(strict_types=1);

namespace Lion\Helpers;

use Closure;
use InvalidArgumentException;
use Lion\Helpers\Str;

/**
 * Modify and build arrays with different indexes or values.
 *
 * @var array|object $items [Array containing current values]
 *
 * @package Lion\Helpers
 */
class Arr
{
    /**
     * [Array containing current values]
     *
     * @var array|object $items
     */
    private array|object $items = [];

    /**
     * Resets the class property to its original value
     *
     * @return void
     */
    private function clean(): void
    {
        $this->items = [];
    }

    /**
     * Convert object to current array
     *
     * @return Arr
     */
    public function toObject(): Arr
    {
        $this->items = (object) $this->items;

        return $this;
    }

    /**
     * Get the indexes of the current array
     *
     * @return Arr
     */
    public function keys(): Arr
    {
        $this->items = array_keys($this->items);

        return $this;
    }

    /**
     * Get the values of the current array
     *
     * @return Arr
     */
    public function values(): Arr
    {
        $this->items = array_values($this->items);

        return $this;
    }

    /**
     * Gets the current value
     *
     * @return array|object
     */
    public function get(): array|object
    {
        $items = $this->items;

        $this->clean();

        return $items;
    }

    /**
     * Add an element to the current array
     *
     * @param mixed $value [Value added to the array]
     * @param int|string $key [Index with which the value is added to the array]
     *
     * @return Arr
     */
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
     *
     * @param array $items [Array containing current values]
     *
     * @return Arr
     */
    public function of(array $items): Arr
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Gets the number of characters in the current string
     *
     * @return int
     */
    public function length(): int
    {
        return count($this->items);
    }

    /**
     * Joins the values of the current array in string format using the defined
     * delimiter
     *
     * @param string $separator [Text that separates the texts in the string]
     * @param null|string $lastSeparator [Text that separates the texts in the
     * string in the last part]
     *
     * @return string
     */
    public function join(string $separator = ', ', ?string $lastSeparator = null): string
    {
        $items = $this->items;

        $this->clean();

        if (null === $lastSeparator) {
            return implode($separator, $items);
        }

        if (count($items) <= 1) {
            return implode($separator, $items);
        }

        $lastElement = array_pop($items);

        return implode($separator, $items) . "{$lastSeparator}{$lastElement}";
    }

    /**
     * Uses the value of a column of a matrix as the key of the same matrix
     *
     * @param string $column [Column name]
     *
     * @return Arr
     */
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
     *
     * @param string $column [Column name]
     *
     * @return Arr
     */
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
     *
     * @param string $value [Value to add to current array]
     * @param string $key [Index to add to current array]
     *
     * @return Arr
     */
    public function prepend(string $value, string $key = ''): Arr
    {
        $this->items = '' === $key ? [$value, ...$this->items] : [$key => $value, ...$this->items];

        return $this;
    }

    /**
     * Select a number of random elements from an array
     *
     * @param int $limit [Number of elements obtained from the current array]
     *
     * @return Arr
     */
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
     *
     * @param Closure $callback [Executes a function that determines whether an
     * element is added to the current array]
     *
     * @return Arr
     */
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
     *
     * @return Arr
     */
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
     *
     * @return mixed
     */
    public function first(): mixed
    {
        $value = reset($this->items);

        $this->clean();

        return $value;
    }

    /**
     * Gets the last element of the current array
     *
     * @return mixed
     */
    public function last(): mixed
    {
        $new_arr = end($this->items);

        $this->clean();

        return $new_arr;
    }

    /**
     * Create a new array with a value inside
     *
     * @param mixed $value [Value that is added to the current array]
     *
     * @return Arr
     */
    public function wrap(mixed $value = null): Arr
    {
        $this->items = empty($value) ? [] : [$value];

        return $this;
    }
}

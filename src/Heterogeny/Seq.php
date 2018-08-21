<?php
/**
 * `Seq` is an `array` without keys, respecting single responsibility principle,
 * `Seq` will never accept keyed items that is not an `integer` index.
 *
 * The use of `\ArrayAccess` is acceptable but not recommended.
 *
 * PHP Version 7.1
 *
 * @category Heterogeny
 * @package  Heterogeny
 *
 * @author  Wesley Willian Schleumer de Góes <me@ues.li>
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License v3
 * @link    https://github.com/schleumer/php-heterogeny
 */

namespace Heterogeny;

/**
 * `Seq` is an `array` without keys, respecting single responsibility principle,
 * `Seq` will never accept keyed items that is not an `integer` index.
 *
 * The use of `\ArrayAccess` is acceptable but not recommended.
 *
 * @category Heterogeny
 * @package  Heterogeny
 *
 * @author  Wesley Willian Schleumer de Góes <me@ues.li>
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License v3
 * @link    https://github.com/schleumer/php-heterogeny
 */
class Seq extends Clonable implements Heterogenic
{
    use Helpers;
    use Equals;

    protected $data;

    /**
     * `Seq`'s constructor
     *
     * @param array $input value which `Seq` will be constructed
     */
    public function __construct(array $input = [])
    {
        // `array_values` is used to avoid PHP from fuzzing indices
        $this->data = array_values($input);
    }

    /**
     * `ArrayAccess` stuff
     *
     * @param mixed $offset offset to set
     * @param mixed $value value
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $offset = $this->sanitizeOffset($offset);

        $this->data[$offset] = $value;
    }

    /**
     * `ArrayAccess` stuff
     *
     * @param mixed $offset offset to lookup
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        $offset = $this->sanitizeOffset($offset);

        return array_key_exists($offset, $this->data);
    }

    /**
     * `ArrayAccess` stuff
     *
     * @param mixed $offset offset to lookup
     *
     * @return mixed
     *
     * @throws \OutOfBoundsException
     */
    public function offsetGet($offset)
    {
        $offset = $this->sanitizeOffset($offset);

        if (!$this->offsetExists($offset)) {
            throw new \OutOfBoundsException($offset);
        }

        return $this->data[$offset];
    }

    /**
     * `ArrayAccess` stuff
     *
     * @param mixed $offset offset to lookup
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        if (!$this->offsetExists($offset)) {
            throw new \OutOfBoundsException($offset);
        }

        unset($this->data[$offset]);
    }

    /**
     * Serializes the object
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->data);
    }

    /**
     * Unserialize the string into object
     *
     * @param mixed $data data to unserialize
     *
     * @return void
     */
    public function unserialize($data): void
    {
        $this->data = unserialize($data);
    }

    /**
     * Count things in the `Seq`
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Gets the `Seq`'s iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * Validates the offset throw an exception if invalid
     *
     * @param mixed $offset offset to lookup
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    protected function sanitizeOffset($offset): int
    {
        if (!is_numeric($offset)) {
            throw new \InvalidArgumentException(
                sprintf('`$index` must be an `int`, %s(%s) received', gettype($offset), var_export($offset, true))
            );
        }

        return intval($offset);
    }

    /**
     * Checks for `Seq`'s emptiness
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    /**
     * Check whether `Seq` is not empty
     *
     * @return bool
     */
    public function nonEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Gets the value at `$index`, if not a valid index, returns `$default`.
     *
     * @param int $index index to lookup
     * @param mixed $default value to return if lookup finds no result
     *
     * @return mixed|null
     */
    public function getOrElse($index, $default = null)
    {
        if (!$this->offsetExists($index)) {
            return $default;
        }

        return $this->offsetGet($index);
    }

    /**
     * Gets the value at `$index`, if not a valid index,
     * `\OutOfBoundsException` will be thrown.
     *
     * @param int $index index to lookup
     *
     * @return mixed
     *
     * @throws \OutOfBoundsException
     */
    public function get($index)
    {
        return $this->offsetGet($index);
    }

    public function set($index, $value = null)
    {
        // TODO: Implement set() method.
    }

    public function del($index)
    {
        // TODO: Implement del() method.
    }

    /**
     * Returns the head or `$default`
     *
     * @param null $default value which will be returned if `Seq` is empty
     *
     * @return mixed|null
     */
    public function head($default = null)
    {
        if (!$this->offsetExists(0)) {
            return $default;
        }

        return $this->data[0];
    }

    /**
     * Returns the last or `$default`
     *
     * @param mixed $default value which will be returned if `Seq` is empty
     *
     * @return mixed|null
     */
    public function last($default = null)
    {
        if ($this->count() < 1) {
            return $default;
        }

        // end() would put cursor at end of array
        // and it's supposed to be immutable
        return $this->data[$this->count() - 1];
    }

    /**
     * Slice the seq from index $index with length $length,
     * as of PHP has no method overloading, $length default value is null.
     */
    public function slice(int $index = 0, ?int $length = null): Seq
    {
        return new Seq(array_slice($this->data, $index, $length));
    }

    /**
     * Takes first n elements of the seq
     */
    public function take(int $n = 0): Seq
    {
        return $this->slice(0, abs($n));
    }

    /**
     * Takes first n elements of the seq
     */
    public function takeLeft(int $n = 0): Seq
    {
        return $this->take($n);
    }

    /**
     * Takes last n elements of the seq
     */
    public function takeRight(int $n = 0): Seq
    {
        return $this->slice(-abs($n));
    }

    /**
     * Returns all items except last
     *
     * @return Seq
     */
    public function init(): Seq
    {
        return new Seq(array_slice($this->data, 0, -1));
    }

    /**
     * Returns all items except head
     *
     * @return Seq
     */
    public function tail(): Seq
    {
        return new Seq(array_slice($this->data, 1));
    }

    /**
     * Returns a `Tuple` containing `init :: last`
     *
     * @return Tuple
     */
    public function initAndLast(): Tuple
    {
        return new Tuple([$this->init(), $this->last()]);
    }

    /**
     * Returns a `Tuple` containing `head :: tail`
     *
     * @return Tuple
     */
    public function headAndTail(): Tuple
    {
        return new Tuple([$this->head(), $this->tail()]);
    }

    /**
     * Appends item to the `Seq`
     *
     * @param mixed $item item which will be appended
     *
     * @return Seq
     */
    public function append($item): Seq
    {
        return new Seq(Utils::arrayAppend($this->data, $item));
    }

    /**
     * Prepends item to the `Seq`
     *
     * @param mixed $item item which will be prepended
     *
     * @return Seq
     */
    public function prepend($item): Seq
    {
        return new Seq(Utils::arrayPrepend($this->data, $item));
    }

    /**
     * Prepend all items in `$other` to the `Seq`
     *
     * @param Seq|array $other other `Seq` which will be prepended
     *
     * @return Seq
     */
    public function prependAll($other): Seq
    {
        if (is_array($other)) {
            return new Seq(
                array_merge(
                    array_values($other),
                    $this->data
                )
            );
        }

        if ($other instanceof Seq) {
            return $other->appendAll($this->data);
        }

        throw new \InvalidArgumentException('`$other` must be `Seq` or `array`');
    }

    /**
     * Append all items in `$other` to the `Seq`
     *
     * @param Seq|array $other other `Seq` which will be appended
     *
     * @return Seq
     */
    public function appendAll($other): Seq
    {
        if (is_array($other)) {
            return new Seq(
                array_merge(
                    $this->data,
                    array_values($other)
                )
            );
        }

        if ($other instanceof Seq) {
            return $other->prependAll($this->data);
        }

        throw new \InvalidArgumentException('`$other` must be `Seq` or `array`');
    }

    /**
     * Shorthand for `equals`
     *
     * @param array|Seq $other other `Seq` to compare
     *
     * @return bool
     */
    public function eq($other): bool
    {
        return $this->equals($other);
    }

    /**
     * Deep strict comparison, preferable over `==`
     *
     * @param array|Seq $other other `Seq` to compare
     *
     * @return bool
     */
    public function equals($other): bool
    {
        if (is_array($other) || $other instanceof self) {
            return count($other) === count($this) &&
                $this->zip($other)->every(
                    function ($tuple) {
                        list($a, $b) = $tuple;

                        if ($a instanceof Equalable) {
                            return $a->equals($b);
                        }

                        return $a === $b;
                    }
                );
        }

        throw new \InvalidArgumentException('`$other` must be `Seq` or `array`');
    }

    /**
     * Map over each `Seq` item
     *
     * @param callable $function map function
     *
     * @return static
     */
    public function map(callable $function)
    {
        return new Seq(array_map($function, $this->data));
    }

    /**
     * Map over each `Seq` item with index
     *
     * @param callable $function map function
     *
     * @return static
     */
    public function mapWithIndex(callable $function)
    {
        $values = array_values($this->data);

        return new Seq(array_map($function, array_keys($values), $values));
    }


    /**
     * Filter an Seq using a predicate which receives value return a new `Seq` with elements
     * that doesn't match the predicate
     *
     * @param callable $function
     * @return Seq
     */
    public function filter(callable $function): Seq
    {
        return new Seq(array_filter($this->data, $function));
    }

    /**
     * Filter an Seq using a predicate which receives key and value return a new `Seq` with elements
     * that doesn't match the predicate
     *
     * @param callable $function
     * @return Seq
     */
    public function filterWithIndex(callable $function): Seq
    {
        $result = new Seq();

        foreach ($this->data as $key => $value) {
            if (!!$function($key, $value)) {
                $result = $result->append($value);
            }
        }

        return $result;
    }

    /**
     * Folds `Seq` items to the left
     *
     * @param callable $function function which will fold values
     * @param mixed $initial the initial fold value
     *
     * @return mixed|null
     */
    public function foldLeft(callable $function, $initial = null)
    {
        if ($this->isEmpty()) {
            return $initial;
        }

        return array_reduce($this->data, $function, $initial);
    }

    /**
     * Folds `Seq` items to the right
     *
     * @param callable $function function which will fold values
     * @param mixed $initial the initial fold value
     *
     * @return mixed|null
     */
    public function foldRight(callable $function, $initial = null)
    {
        if ($this->isEmpty()) {
            return $initial;
        }

        return array_reduce(array_reverse($this->data, true), $function, $initial);
    }

    /**
     * Reduces `Seq` items to the left
     *
     * Reduce does not take any initial value, instead,
     * it uses head as initial.
     *
     * @param callable $function function which will reduce the Seq
     *
     * @return mixed|null
     */
    public function reduceLeft(callable $function)
    {
        if ($this->isEmpty()) {
            return null;
        }

        list($head, $tail) = $this->headAndTail();

        return array_reduce($tail->all(), $function, $head);
    }

    /**
     * Reduces `Seq` items to the right
     *
     * Reduce does not take any initial value, instead,
     * it uses head as initial.
     *
     * @param callable $function function which will reduce the Seq
     *
     * @return mixed|null
     */
    public function reduceRight(callable $function)
    {
        if ($this->isEmpty()) {
            return null;
        }

        list($head, $tail) = $this->reverse()->headAndTail();

        return array_reduce($tail->all(), $function, $head);
    }

    /**
     * Zip with another `Seq` values on the left
     *
     * @param array|Seq $other another list
     *
     * @return static
     */
    public function zipLeft($other)
    {
        if (count($other) !== count($this)) {
            throw new \InvalidArgumentException(
                'Both left and right must have same size.'
            );
        }

        if (is_array($other)) {
            return new Seq(
                array_map(
                    function ($a, $b) {
                        return new Tuple([$a, $b]);
                    },
                    $other,
                    $this->data
                )
            );
        }

        if ($other instanceof Seq) {
            return $other->zip($this->data);
        }

        throw new \InvalidArgumentException('`$other` must be `Seq` or `array`');
    }

    /**
     * Zip with another `Seq` values on the right
     *
     * @param array|Seq $other another list
     *
     * @return Seq
     */
    public function zip($other): Seq
    {
        if (count($other) !== count($this)) {
            throw new \InvalidArgumentException(
                'Both left and right must have same size.'
            );
        }

        if (is_array($other)) {
            return new Seq(
                array_map(
                    function ($a, $b) {
                        return new Tuple([$a, $b]);
                    },
                    $this->data,
                    $other
                )
            );
        }

        if ($other instanceof Seq) {
            return $other->zipLeft($this->data);
        }

        throw new \InvalidArgumentException('`$other` must be `Seq` or `array`');
    }

    /**
     * Check if all Seq items match the predicate `$function`
     *
     * @param callable $function `every`'s predicate
     *
     * @return bool result
     */
    public function every(callable $function): bool
    {
        return $this->foldLeft(
            function ($acc, $item) use ($function) {
                return $acc && $function($item);
            },
            true
        );
    }

    /**
     * Check f any Seq items match the predicate `$function`
     *
     * @param callable $function `some`'s predicate
     *
     * @return bool result
     */
    public function some(callable $function): bool
    {
        return $this->foldLeft(
            function ($acc, $item) use ($function) {
                return $acc || $function($item);
            },
            false
        );
    }

    /**
     * Reverses `Seq` items
     *
     * @return Seq
     */
    public function reverse(): Seq
    {
        return new Seq(array_reverse($this->data));
    }

    public function mkString($separator): string
    {
        return join($separator, $this->data);
    }

    /**
     * `JsonSerializable` implementation
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return array_values($this->data);
    }
}

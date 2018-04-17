<?php

/**
 * `Dict` is an dictionary(or hashmap, or just map).
 *
 * Unlike `Seq`, `Dict` will accept everything(supported by PHP) as
 * key.
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
 * `Dict` is an dictionary(or hashmap, or just map).
 *
 * Unlike `Seq`, `Dict` will accept everything(supported by PHP) as
 * key.
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
class Dict extends Clonable implements Heterogenic
{
    use Helpers;
    use Equals;

    /**
     * `Seq`'s constructor
     *
     * @param array $input value which `Seq` will be constructed
     */
    public function __construct(array $input = [])
    {
        $this->data = $input;
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
        $this->data[$offset] = $value;
    }

    /**
     * `ArrayAccess` stuff
     *
     * @param mixed $offset offset to lookup
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
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
        if (!$this->offsetExists($offset)) {
            throw new \OutOfBoundsException("$offset");
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
            throw new \OutOfBoundsException();
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
     * Count things in the `Dict`
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Gets the `Dict`'s iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * Check `Seq` is emptiness
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    /**
     * Check `Seq` is not empty
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
     * @param mixed $index index to lookup
     * @param mixed $default value to return if lookup finds no result
     *
     * @return mixed|null
     */
    public function getOrElse($index, $default = null)
    {
        $focus = Aim::focus($index);

        if (!$focus->exists($this)) {
            return $default;
        }

        return $focus->get($this);
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
        $focus = Aim::focus($index);

        if (!$focus->exists($this)) {
            throw new \OutOfBoundsException($index);
        }

        return $focus->get($this);
    }

    public function set($index, $value = null)
    {
        $focus = Aim::focus($index);

        return $focus->set($this, $value);
    }

    public function del($index)
    {
        $focus = Aim::focus($index);

        return $focus->del($this);
    }

    public function merge($other): Dict
    {
        if (is_array($other)) {
            $other = new Dict($other);
        }

        return $other->toPairs()->foldLeft(function (Dict $target, Pair $pair) {
            $target->offsetSet($pair->key, $pair->value);

            return $target;
        }, $this->copy());
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
     * @param array|Dict $other other `Dict` to compare
     *
     * @return bool
     */
    public function equals($other): bool
    {
        if (is_array($other) || $other instanceof Dict) {
            return count($other) === count($this) &&
                $this->zip($other)->every(function ($tuple) {
                    list($a, $b) = $tuple;

                    if ($a instanceof Equalable) {
                        return $a->equals($b);
                    }

                    return $a === $b;
                });
        }

        throw new \InvalidArgumentException('`$other` must be `Seq` or `array`');
    }

    /**
     * Map over each item
     *
     * @param callable $function map function
     *
     * @return static
     */
    public function map(callable $function)
    {
        $output = new Dict();

        foreach ($this->data as $key => $value) {
            $output->offsetSet($key, $function($key, $value));
        }

        return $output;
    }

    /**
     * Filter an Dict using a predicate which receives key and value return a new `Dict` with elements
     * that doesn't match the predicate
     *
     * @param callable $function
     * @return Dict
     */
    public function filter(callable $function): Dict
    {
        $result = new Dict();

        foreach ($this->data as $key => $value) {
            if (!!$function($key, $value)) {
                $result->offsetSet($key, $value);
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
     * Zip with another `Dict` values on the left
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
            $aKeys = array_keys($this->data);
            $other = new Dict($other);

            return new Dict(
                array_map(
                    function ($key) use ($other) {
                        $leftValue = $this->getOrElse($key);
                        $rightValue = $other->getOrElse($key);

                        return new Tuple([$rightValue, $leftValue]);
                    },
                    $aKeys
                )
            );
        }

        if ($other instanceof Dict) {
            return $other->zipLeft($this->data);
        }

        throw new \InvalidArgumentException('`$other` must be `Dict` or `array`');
    }

    /**
     * Zip with another `Dict` values on the right
     *
     * @param array|Seq $other another list
     *
     * @return static
     */
    public function zip($other)
    {
        if (count($other) !== count($this)) {
            throw new \InvalidArgumentException(
                'Both left and right must have same size.'
            );
        }

        if (is_array($other)) {
            $aKeys = array_keys($this->data);
            $other = new Dict($other);

            return new Dict(
                array_map(
                    function ($key) use ($other) {
                        $leftValue = $this->getOrElse($key);
                        $rightValue = $other->getOrElse($key);

                        return new Tuple([$leftValue, $rightValue]);
                    },
                    $aKeys
                )
            );
        }

        if ($other instanceof Dict) {
            return $other->zipLeft($this->data);
        }

        throw new \InvalidArgumentException('`$other` must be `Dict` or `array`');
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
     * Check if any items match the predicate `$function`
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

    public function values()
    {
        return array_values($this->data);
    }

    public function keys()
    {
        return array_keys($this->data);
    }

    public function toPairs()
    {
        $pairs = $this->map(function ($key, $value) {
            return new Pair($key, $value);
        });

        return new Pairs($pairs->values());
    }

    public function toSeq()
    {
        return new Seq(array_values($this->data));
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return (object)$this->data;
    }
}

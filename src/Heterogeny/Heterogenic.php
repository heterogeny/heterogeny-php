<?php

/**
 * Describes every action which is avaiable from Heterogeny data structures.
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
 * Describes every action which is avaiable from Heterogeny data structures.
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
interface Heterogenic extends
    \IteratorAggregate,
    \ArrayAccess,
    \Serializable,
    \Countable,
    \JsonSerializable,
    Equalable
{
    /**
     * @return Heterogenic
     */
    public function copy();

    /**
     * Checks for emptiness
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Check whether its not empty
     *
     * @return bool
     */
    public function nonEmpty(): bool;

    /**
     * Gets the value at `$index`, if not a valid index, returns `$default`.
     *
     * @param int $index index to lookup
     * @param mixed $default value to return if lookup finds no result
     *
     * @return mixed|null
     */
    public function getOrElse($index, $default = null);


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
    public function get($index);

    public function set($index, $value = null);

    public function del($index);

    /**
     * Map over each item
     *
     * @param callable $function map function
     *
     * @return static
     */
    public function map(callable $function);

    /**
     * Zip with another structure values on the left
     *
     * @param array|Seq $other another list
     *
     * @return static
     */
    public function zipLeft($other);

    /**
     * Zip with another `Seq` values on the right
     *
     * @param array|Seq $other another list
     *
     * @return static
     */
    public function zip($other);

    /**
     * Check if all items match the predicate `$function`
     *
     * @param callable $function `every`'s predicate
     *
     * @return bool result
     */
    public function every(callable $function): bool;

    /**
     * Check if any items match the predicate `$function`
     *
     * @param callable $function `some`'s predicate
     *
     * @return bool result
     */
    public function some(callable $function): bool;

    /**
     * Will assume that $this is `Seq` otherwise an exception will be thrown.
     *
     * It's just an cosmetic method that can help with intellisense or for validation.
     *
     * @return Dict
     */
    public function dict(): Dict;

    /**
     * Will assume that $this is `Seq` otherwise an exception will be thrown.
     *
     * It's just an cosmetic method that can help with intellisense or for validation.
     *
     * @return Seq
     */
    public function seq(): Seq;

    /**
     * Return the native representation of the object
     *
     * @return array
     */
    public function all(): array;
}

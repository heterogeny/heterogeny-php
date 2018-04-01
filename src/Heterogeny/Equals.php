<?php

/**
 * Equalable helper
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
 * Equalable helper
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
trait Equals
{
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
        if (is_array($other) || $other instanceof Heterogenic) {
            return count($other) === count($this) &&
                $this->zip($other)->every(
                    function ($tuple) {
                        list($a, $b) = $tuple;

                        if ($a instanceof Heterogenic) {
                            return $a->equals($b);
                        }

                        return $a === $b;
                    }
                );
        }

        throw new \InvalidArgumentException('`$other` must be `Seq` or `array`');
    }
}

<?php

namespace Heterogeny;

class Utils
{
    /**
     * Prepend item to array without modifying original array
     * and returning a new array.
     *
     * @param $a
     * @param $b
     * @return array
     */
    public static function arrayPrepend($a, $b): array
    {
        array_unshift($a, $b);

        return $a;
    }

    /**
     * Append item to array without modifying original array
     * and returning a new array.
     *
     * @param $a
     * @param $b
     * @return array
     */
    public static function arrayAppend($a, $b): array
    {
        $a[] = $b;

        return $a;
    }
}

<?php

namespace Heterogeny;

/**
 * Serious JSON
 *
 * @package Heterogeny
 */
class JSON
{
    /**
     * Translate json_decode to `Dict`/`Seq`
     *
     * @param $value
     * @return Heterogenic
     */
    protected static function fix($value)
    {
        if (is_array($value)) {
            return new Seq(array_map(function ($item) {
                return self::fix($item);
            }, $value));
        }

        // kinda redundant
        if (is_object($value) && get_class($value) === \stdClass::class) {
            return new Dict(array_map(function ($item) {
                return self::fix($item);
            }, (array)$value));
        }

        return $value;
    }

    /**
     * @param string $json
     *
     * @return Heterogenic|null
     */
    public static function decode(string $json): ?Heterogenic
    {
        return self::fix(json_decode($json));
    }

    /**
     * @param Heterogenic $data Any `Heterogenic` data
     * @param mixed $flags `json_encode` flags
     *
     * @return string
     */
    public static function encode(?Heterogenic $data, $flags = null): string
    {
        return json_encode($data, $flags);
    }
}

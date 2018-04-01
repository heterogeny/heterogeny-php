<?php

namespace Heterogeny;

class Aim
{
    public static function exists($path): callable
    {
        $focus = self::focus($path);

        return function (Heterogenic $target) use ($focus) {
            return $focus->exists($target);
        };
    }

    public static function get($path): callable
    {
        $focus = self::focus($path);

        return function (Heterogenic $target) use ($focus) {
            return $focus->get($target);
        };
    }

    public static function del($path): callable
    {
        $focus = self::focus($path);

        return function (Heterogenic $target) use ($focus) {
            return $focus->del($target);
        };
    }

    public static function set($path, $value): callable
    {
        $focus = self::focus($path);

        return function (Heterogenic $target) use ($focus, $value) {
            return $focus->set($target, $value);
        };
    }

    public static function update($path, $fn): callable
    {
        $focus = self::focus($path);

        return function (Heterogenic $target) use ($focus, $fn) {
            return $focus->update($target, $fn);
        };
    }

    public static function focus($path, $separator = '/')
    {
        $path = explode($separator, $path);

        return new Focus($path);
    }
}

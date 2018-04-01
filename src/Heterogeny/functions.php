<?php

function seq(...$args)
{
    return new \Heterogeny\Seq($args);
}

function tuple(...$args)
{
    return new \Heterogeny\Tuple($args);
}

function dict(array $input = [])
{
    return new \Heterogeny\Dict($input);
}

if (defined('IN_TEST') && IN_TEST) {
    function dd(...$values)
    {
        dump(...$values);
        exit;
    }
}

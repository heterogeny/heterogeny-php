<?php

namespace Heterogeny;

final class Pairs extends Seq
{
    public function toDict(): Dict
    {
        return $this->foldLeft(function (Dict $dict, Pair $pair) {
            return $dict->set($pair->key, $pair->value);
        }, new Dict());
    }
}

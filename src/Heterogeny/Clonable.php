<?php

namespace Heterogeny;

abstract class Clonable
{
    protected $data;

    abstract public function __construct(array $input = []);

    public function copy()
    {
        $newData = array_map(function ($item) {
            if ($item instanceof Clonable) {
                return $item->copy();
            }

            return $item;
        }, $this->data);

        return new static($newData);
    }
}

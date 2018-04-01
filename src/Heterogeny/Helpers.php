<?php

namespace Heterogeny;

trait Helpers
{
    public function all(): array
    {
        return array_map(function ($item) {
            if ($item instanceof Heterogenic) {
                return $item->all();
            }

            return $item;
        }, $this->data);
    }
    
    public function isDict(): bool
    {
        return $this instanceof Dict;
    }

    public function isSeq(): bool
    {
        return $this instanceof Seq;
    }

    public function isTuple(): bool
    {
        return $this instanceof Tuple;
    }

    /**
     * @return Dict
     * @throws \Exception
     */
    public function dict(): Dict
    {
        if ($this instanceof Dict) {
            return $this;
        }

        throw new \Exception(sprintf('Cannot assume that `%s` is `Dict`', get_class($this)));
    }

    /**
     * @return Seq
     * @throws \Exception
     */
    public function seq(): Seq
    {
        if ($this instanceof Seq) {
            return $this;
        }

        throw new \Exception(sprintf('Cannot assume that `%s` is `Seq`', get_class($this)));
    }
}

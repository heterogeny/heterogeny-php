<?php

namespace Heterogeny;

class Focus
{
    private $path;

    public function __construct(array $path)
    {
        $this->path = seq(...$path);
    }

    public function get(Heterogenic $target)
    {
        /** @var Heterogenic|mixed $currentNode */
        $currentNode = $target;

        foreach ($this->path as $section) {
            if ($currentNode->offsetExists($section)) {
                $currentNode = $currentNode->offsetGet($section);
                continue;
            }

            return null;
        }

        return $currentNode;
    }

    /**
     * TODO: this could be really really really really really better
     *
     * @param Heterogenic $target
     * @param $value
     * @return mixed
     */
    public function set(Heterogenic $target, $value)
    {
        /* @var Seq $path */
        list($path, $last) = $this->path->initAndLast();

        $clonedTarget = $target->copy();

        /** @var Heterogenic|mixed $currentNode */
        $currentNode = $clonedTarget;

        $path = $path->all();

        for ($index = 0; $index < count($path); $index++) {
            $section = $path[$index];

            $next = null;
            if (key_exists($index + 1, $path)) {
                $next = next($path);
                prev($path);
            }

            if ($currentNode->offsetExists($section)) {
                $seekNode = $currentNode->offsetGet($section);

                if (!$seekNode instanceof Heterogenic) {
                    if (is_numeric($next)) {
                        $newNode = seq();
                    } else {
                        $newNode = dict();
                    }

                    $currentNode->offsetSet($section, $newNode);
                    $currentNode = $newNode;
                } else {
                    $currentNode = $seekNode;
                }
            } else {
                if (is_numeric($next)) {
                    $newNode = seq();
                } else {
                    $newNode = dict();
                }

                $currentNode->offsetSet($section, $newNode);
                $currentNode = $newNode;
            }
        }

        $currentNode->offsetSet($last, $value);

        return $clonedTarget;
    }

    public function update(Heterogenic $target, callable $fn)
    {
        $originalValue = $this->get($target);

        return $this->set($target, $fn($originalValue));
    }

    public function exists(Heterogenic $target)
    {
        /** @var Heterogenic|mixed $currentNode */
        $currentNode = $target;

        foreach ($this->path as $section) {
            if ($currentNode instanceof Heterogenic &&
                $currentNode->offsetExists($section)
            ) {
                $currentNode = $currentNode->offsetGet($section);
                continue;
            }

            return false;
        }

        return true;
    }

    public function del(Heterogenic $target)
    {
        list($path, $last) = $this->path->initAndLast();

        $clonedTarget = $target->copy();

        /** @var Heterogenic|mixed $currentNode */
        $rootNode = $clonedTarget;

        foreach ($path as $section) {
            if ($rootNode instanceof Heterogenic &&
                $rootNode->offsetExists($section)
            ) {
                $rootNode = $rootNode->offsetGet($section);
                continue;
            }

            $rootNode = null;
            break;
        }

        if ($rootNode instanceof Heterogenic) {
            if ($rootNode->offsetExists($last)) {
                $rootNode->offsetUnset($last);
            }
        }

        return $clonedTarget;
    }
}

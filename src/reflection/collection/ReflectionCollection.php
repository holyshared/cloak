<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak\reflection\collection;


use PhpCollection\Sequence;
use PhpCollection\Map;
use cloak\collection\PairStackable;
use cloak\reflection\Reflection;
use cloak\reflection\ResultConvertible;
use cloak\Collection;
use cloak\result\LineResultSelectable;
use cloak\result\collection\CoverageResultCollection;
use \Closure;
use \Iterator;
use \ArrayIterator;


/**
 * Class ReflectionCollection
 * @package cloak\reflection\collection
 */
class ReflectionCollection implements Collection, ResultCollectionConvertible
{

    use PairStackable;


    /**
     * @param \cloak\reflection\Reflection[] $reflections
     */
    public function __construct(array $reflections = [])
    {
        $this->collection = new Map();
        $this->addAll($reflections);
    }

    /**
     * @param Reflection $reflection
     */
    public function add(Reflection $reflection)
    {
        $identityName = $reflection->getIdentityName();
        $this->collection->set($identityName, $reflection);
    }

    /**
     * @param Reflection[] $reflections
     */
    public function addAll(array $reflections)
    {
        $this->pushAll(new ArrayIterator($reflections));
    }

    /**
     * @param ReflectionCollection $reflections
     */
    public function merge(ReflectionCollection $reflections)
    {
        $this->pushAll( $reflections->getIterator() );
    }

    /**
     * @param Iterator $reflections
     */
    private function pushAll(Iterator $reflections)
    {
        foreach ($reflections as $reflection) {
            $this->add($reflection);
        }
    }

    /**
     * @param callable $filter
     * @return ReflectionCollection
     */
    public function filter(Closure $filter)
    {
        $collection = $this->collection->filter($filter);
        return new self( $collection->values() );
    }


    /**
     * {@inheritdoc}
     */
    public function convertToResult(LineResultSelectable $selector)
    {
        $values = $this->collection->values();
        $collection = new Sequence($values);

        $convertCallback = function(ResultConvertible $reflection) use($selector) {
            return $reflection->convertToResult($selector);
        };
        $results = $collection->map($convertCallback);

        return new CoverageResultCollection( $results->all() );
    }

}

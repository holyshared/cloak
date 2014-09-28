<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak\result;

use cloak\value\LineRange;
use Zend\Code\Reflection\ClassReflection;

/**
 * Class ClassResult
 * @package cloak\result
 */
final class ClassResult extends NamedCoverageResult
{

    /**
     * @var ClassReflection
     */
    private $reflection;


    /**
     * @param ClassReflection $classReflection
     * @param LineSet $classLineResults
     */
    public function __construct(ClassReflection $classReflection, LineSetInterface $classLineResults)
    {
        $lineRange = new LineRange(
            $classReflection->getStartLine(),
            $classReflection->getEndLine()
        );
        $rangeResults = $classLineResults->selectRange($lineRange);

        $this->reflection = $classReflection;
        $this->lineResults = $rangeResults;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->reflection->getName();
    }

    /**
     * @return string
     */
    public function getNamespaceName()
    {
        return $this->reflection->getNamespaceName();
    }

}

<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak\result\specification;

use cloak\result\SpecificationInterface;
use cloak\result\CoverageResultInterface;


/**
 * Class Satisfactory
 * @package cloak\result\specification
 */
class Satisfactory extends CoverageSpecification implements SpecificationInterface
{

    /**
     * @param CoverageResultInterface $result
     * @return bool
     */
    public function match(CoverageResultInterface $coverageResult)
    {
        return $coverageResult->isCoverageGreaterEqual($this->coverage);
    }

}

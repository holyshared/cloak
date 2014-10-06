<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak\spec\result;

use cloak\result\CoverageResult;
use cloak\result\LineResultCollectionInterface;


class FixtureCoverageResult
{

    use CoverageResult;


    public function __construct(LineResultCollectionInterface $lineResults)
    {
        $this->lineResults = $lineResults;
    }

}

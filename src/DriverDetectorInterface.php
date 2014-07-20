<?php

/**
 * This file is part of easy-coverage.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace easycoverage;

/**
 * Interface DriverDetectorInterface
 * @package easycoverage
 */
interface DriverDetectorInterface
{

    /**
     * @return \easycoverage\driver\DriverInterface
     * @throws \easycoverage\DriverNotFoundException
     */
    public function detect();

}

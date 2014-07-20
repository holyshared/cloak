<?php

/**
 * This file is part of easy-coverage.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace easycoverage\reporter;

use easycoverage\EventInterface,
    Zend\EventManager\ListenerAggregateInterface;

interface ReporterInterface extends ListenerAggregateInterface
{

    /**
     * @return void
     */
    public function onStop(EventInterface $event);

}

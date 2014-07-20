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

use easycoverage\Result;
use easycoverage\Reporter\ReporterInterface;
use Zend\EventManager\EventManagerAwareTrait;

//TODO setReporter / getReporter
/**
 * Class Notifier
 * @package easycoverage
 */
class Notifier implements NotifierInterface
{

    use EventManagerAwareTrait;

    public function __construct(ReporterInterface $reporter = null)
    {
        if ($reporter === null) { 
            return;
        }
        $reporter->attach( $this->getEventManager() );
    }

    public function stop(Result $result)
    {
        $event = new Event(Event::STOP, $this, [ 'result' => $result ]);
        $this->getEventManager()->trigger($event);
    }

}

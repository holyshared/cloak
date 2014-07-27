<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak;

use cloak\Result;
use cloak\Reporter\ReporterInterface;
use Zend\EventManager\EventManagerAwareTrait;

//TODO setReporter / getReporter
/**
 * Class AnalyzeLifeCycleNotifier
 * @package cloak
 */
class AnalyzeLifeCycleNotifier implements AnalyzeLifeCycleNotifierInterface
{

    use EventManagerAwareTrait;

    /**
     * @param ReporterInterface $reporter
     */
    public function __construct(ReporterInterface $reporter = null)
    {
        if ($reporter === null) { 
            return;
        }
        $reporter->attach( $this->getEventManager() );
    }

    /**
     * @param Result $result
     */
    public function notifyStop(Result $result)
    {
        $event = new Event(Event::STOP, $this, [ 'result' => $result ]);
        $this->getEventManager()->trigger($event);
    }

}

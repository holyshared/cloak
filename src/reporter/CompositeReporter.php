<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak\reporter;

use cloak\Result;
use cloak\event\StartEventInterface;
use cloak\event\StopEventInterface;

use cloak\event\StartEvent;
use cloak\event\StopEvent;
use PHPExtra\EventManager\EventManager;


/**
 * Class CompositeReporter
 * @package cloak\reporter
 */
class CompositeReporter implements ReporterInterface, StartEventListener, StopEventListener
{

    use Reportable;


    /**
     * @var \PHPExtra\EventManager\EventManager
     */
    private $eventManager;


    /**
     * @param array $reporters
     */
    public function __construct(array $reporters)
    {
        $eventManager = new EventManager();

        foreach ($reporters as $reporter) {
            $eventManager->addListener($reporter);
        }
        $this->eventManager = $eventManager;
    }

    /**
     * @param StartEvent $event
     */
    public function onStart(StartEvent $event)
    {
        $this->eventManager->trigger($event);
    }

    /**
     * @param StopEvent $event
     */
    public function onStop(StopEvent $event)
    {
        $this->eventManager->trigger($event);
    }

}

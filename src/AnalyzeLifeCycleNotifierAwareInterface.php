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

/**
 * Interface AnalyzeLifeCycleNotifierAwareInterface
 * @package cloak
 */
interface AnalyzeLifeCycleNotifierAwareInterface
{

    public function setLifeCycleNotifier(AnalyzeLifeCycleNotifierInterface $notifier);

    public function getLifeCycleNotifier();

}
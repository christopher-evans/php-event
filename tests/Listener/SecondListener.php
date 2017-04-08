<?php
/*
 * This file is part of the West\\Event package
 *
 * (c) Chris Evans <c.m.evans@gmx.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace West\Event\Listener;

use West\Event\Context\TestContext;
use West\Event\EventInterface;

class SecondListener implements ListenerInterface
{
    public function observesContext($context): bool
    {
        return $context instanceof TestContext;
    }

    public function handle(EventInterface $event): bool
    {
        $testData = $event->getParameter('test-data');

        $testData->order = 'second';

        return true;
    }
}

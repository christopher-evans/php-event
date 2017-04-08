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

use West\Event\EventInterface;

/**
 * Representation of an event listener
 */
interface ListenerInterface
{
    public function handle(EventInterface $event): bool;

    public function observesContext($context): bool;
}

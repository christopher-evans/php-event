<?php
/*
 * This file is part of the West\\Event package
 *
 * (c) Chris Evans <cvns.github@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace West\Event\Percipient;

use West\Event\EventInterface;

/**
 * Representation of an event percipient
 */
interface PercipientInterface
{
    public function handle(EventInterface $event): bool;

    public function observesContext($context): bool;
}

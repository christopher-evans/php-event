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

use West\Event\Context\TestContext;
use West\Event\EventInterface;
use West\Event\Exception\EventException;

class ExceptionPercipient implements PercipientInterface
{
    public function observesContext($context): bool
    {
        return $context instanceof TestContext;
    }

    public function handle(EventInterface $event): bool
    {
        throw new EventException();
    }
}

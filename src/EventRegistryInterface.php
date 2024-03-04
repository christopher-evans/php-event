<?php
/*
 * This file is part of the West\\Event package
 *
 * (c) Chris Evans <cvns.github@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace West\Event;

/**
 * Interface for EventRegistry
 */
interface EventRegistryInterface
{
    /**
     * Trigger an event
     *
     * Can accept an EventInterface or will create one if not passed
     *
     * @param  string|EventInterface $event
     * @param  object|string $target
     * @param  array|object $argv
     * @return mixed
     */
    public function trigger(EventInterface $event);
}
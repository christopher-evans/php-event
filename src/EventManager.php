<?php
/*
 * This file is part of the West\\Event package
 *
 * (c) Chris Evans <c.m.evans@gmx.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace West\Event;

use West\Event\Exception\EventException;

final class EventManager implements EventManagerInterface
{
    private $listeners = [];

    public function __construct(iterable $listeners)
    {
        foreach ($listeners as $listener) {
            if (! isset($listener['on']) || ! isset($listener["listener"]) || ! isset($listener['priority'])) {
                throw new InvalidArgumentException('Missing key in listener');
            }

            [
                "on" => $name,
                "listener" => $listener,
                "priority" => $priority
            ] = $listener;

            $this->listeners[$name][$priority][] = $listener;
        }

        foreach ($this->listeners as $eventName => $listeners) {
            // sort events by priority
            ksort($this->listeners[$eventName]);
        }
    }

    public function trigger(EventInterface $event)
    {
        $eventName = $event->getName();
        $result = null;

        if (! array_key_exists($eventName, $this->listeners)) {
            // no listeners
            return $result;
        }

        $listeners = $this->listeners[$eventName];

        // call listeners
        $context = $event->getContext();
        foreach ($listeners as $listenerPriorities) {
            foreach ($listenerPriorities as $listener) {
                if (!$listener->observesContext($context)) {
                    // check is listener observes the event context
                    continue;
                }

                try {
                    $result = $listener->handle($event);
                } catch (EventException $exception) {
                    break 2;
                }
            }
        }

        // return result
        return $result;
    }
}

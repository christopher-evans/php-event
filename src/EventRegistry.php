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
use West\Event\Percipient\PercipientInterface;

final class EventRegistry implements EventRegistryInterface
{
    private $percipients = [];

    public function __construct(iterable $percipientPositions)
    {
        foreach ($percipientPositions as $percipientPosition) {
            [
                'on' => $name,
                'percipient' => $percipient,
                'priority' => $priority
            ] = $this->parsePercipient($percipientPosition);

            $this->percipients[$name][$priority][] = $percipient;
        }

        foreach ($this->percipients as $eventName => $percipients) {
            // sort events by priority
            ksort($this->percipients[$eventName]);
        }
    }

    private function parsePercipient(array $percipientPosition): array
    {
        if (! isset($percipientPosition['on']) || ! isset($percipientPosition['percipient'])
            || ! isset($percipientPosition['priority'])) {
            throw new InvalidArgumentException('Missing key in percipient');
        }

        [
            'on' => $name,
            'percipient' => $percipient,
            'priority' => $priority
        ] = $percipientPosition;

        if (! is_string($name)) {
            throw new InvalidArgumentException('Event name must be a string');
        }

        if (! $percipient instanceof PercipientInterface) {
            throw new InvalidArgumentException('Percipient must implement PercipientInterface');
        }

        if (! is_int($priority)) {
            throw new InvalidArgumentException('Priority must be an integer');
        }

        return $percipientPosition;
    }

    public function trigger(EventInterface $event)
    {
        $eventName = $event->getName();
        $result = null;

        if (! array_key_exists($eventName, $this->percipients)) {
            // no percipients
            return $result;
        }

        $percipients = $this->percipients[$eventName];

        // call percipients
        $context = $event->getContext();
        foreach ($percipients as $percipientPriorities) {
            foreach ($percipientPriorities as $percipient) {
                if (! $percipient->observesContext($context)) {
                    // check is percipient observes the event context
                    continue;
                }

                try {
                    $result = $percipient->handle($event);
                } catch (EventException $exception) {
                    break 2;
                }
            }
        }

        // return result
        return $result;
    }
}

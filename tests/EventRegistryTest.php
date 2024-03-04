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

use PHPUnit\Framework\TestCase;
use stdClass;

class EventRegistryTest extends TestCase
{
    private $context;

    private $eventData;

    private $eventName;

    private $eventParameter;

    private $firstListener;

    private $secondListener;

    private $exceptionListener;

    public function setUp()
    {
        $this->context = new Context\TestContext();
        $this->eventData = new stdClass();
        $this->eventName = 'test-name';
        $this->eventParameter = 'test-data';
        $this->firstListener = new Percipient\FirstPercipient();
        $this->secondListener = new Percipient\SecondPercipient();
        $this->exceptionListener = new Percipient\ExceptionPercipient();
    }

    public function testInvalidConfig()
    {
        $this->expectException(InvalidArgumentException::class);

        $config = [
            [
                'name' => 'name',
                'context' => $this->context
            ]
        ];

        new EventRegistry($config);
    }

    public function testPriority()
    {
        $event = new Event(
            $this->eventName,
            $this->context,
            [
                $this->eventParameter => $this->eventData
            ]
        );

        $eventRegistry = new EventRegistry(
            [
                [
                    'on' => $this->eventName,
                    'priority' => -1,
                    'percipient' => $this->firstListener
                ],
                [
                    'on' => $this->eventName,
                    'priority' => 1,
                    'percipient' => $this->secondListener
                ]
            ]
        );

        $eventRegistry->trigger($event);

        $eventData = $event->getParameter($this->eventParameter);

        $this->assertEquals($eventData->order, 'second');
    }

    public function testContextNoMatch()
    {
        $context = new stdClass();
        $this->eventData->order = 'never';

        $event = new Event(
            $this->eventName,
            $context,
            [
                $this->eventParameter => $this->eventData
            ]
        );

        $eventRegistry = new EventRegistry(
            [
                [
                    'on' => $this->eventName,
                    'priority' => 0,
                    'percipient' => $this->firstListener
                ]
            ]
        );

        $eventRegistry->trigger($event);

        $eventData = $event->getParameter($this->eventParameter);

        $this->assertEquals($eventData->order, 'never');
    }

    public function testNoListeners()
    {
        $this->eventData->order = 'never';

        $event = new Event(
            $this->eventName,
            $this->context,
            [
                $this->eventParameter => $this->eventData
            ]
        );

        $eventRegistry = new EventRegistry([]);

        $eventRegistry->trigger($event);

        $eventData = $event->getParameter($this->eventParameter);

        $this->assertEquals($eventData->order, 'never');
    }

    public function testListenerDistinctEvent()
    {
        $this->eventData->order = 'never';

        $event = new Event(
            $this->eventName,
            $this->context,
            [
                $this->eventParameter => $this->eventData
            ]
        );

        $eventRegistry = new EventRegistry(
            [
                [
                    'on' => 'another-event',
                    'priority' => 0,
                    'percipient' => $this->firstListener
                ]
            ]
        );

        $eventRegistry->trigger($event);

        $eventData = $event->getParameter($this->eventParameter);

        $this->assertEquals($eventData->order, 'never');
    }

    public function testExceptionBreak()
    {
        $this->eventData->order = 'never';

        $event = new Event(
            $this->eventName,
            $this->context,
            [
                $this->eventParameter => $this->eventData
            ]
        );

        $eventRegistry = new EventRegistry(
            [
                [
                    'on' => $this->eventName,
                    'priority' => -1,
                    'percipient' => $this->exceptionListener
                ],
                [
                    'on' => $this->eventName,
                    'priority' => 1,
                    'percipient' => $this->secondListener
                ]
            ]
        );

        $eventRegistry->trigger($event);

        $eventData = $event->getParameter($this->eventParameter);

        $this->assertEquals($eventData->order, 'never');
    }
}

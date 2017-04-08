# West\\Event

An event manager implementation for PHP 7.1+.


## Getting started

An event listener is a class implementing the `Listener\EventListener` interface:
 
```php
namespace West\Event\Listener;

use West\Event\EventInterface;

class FooListener implements ListenerInterface
{
    public function observesContext($context): bool
    {
        return $context instanceof \stdClass;
    }

    public function handle(EventInterface $event): bool
    {
        $parameter = $event->getParameter('event-parameter');

        // do something with $parameter

        return 'return-value';
    }
}
```

A listener can be registered with an event manager instance:

```php
$eventName = 'event-name';
$eventManager = new EventManager(
    [
        [
            'on' => $eventName,
            'priority' => 0,
            'listener' => new FooListener()
        ],
    ]
);
```

Then an event can be triggered:

```php
// event context
$context = new \stdClass();

$event = new Event(
    $eventName,
    $context,
    [
        'event-parameter' => 'parameter-value'
    ]
);

// $result === 'return-value'
$result = $eventManager->trigger($event);
```

A lower priority listener is executed earlier than a higher priority listener. There is no guarantee of the order of execution for two listeners attached with the same priority.


## Events

Event names are case sensitive, alphanumeric strings that may include ```'.', '-', '_'``` at any position.

Event parameters are passed into the constructor as seen above. The parameters can be retrieved in a listener as an array of all parameters:

```php
$event->getParameters();
```

or individually:


```php
$event->getParameter('parameter-name');
```

The method ```EventInterface::getParameter``` will throw a ```West\Event\Exception\InvalidArgumentException``` if the is no parameter with the provided key. Note that objects passed as event parameters will break immutability of the event, so should be avoided.


## Event manager

The event manager constructor expects an array of arrays of the form:

```php
[
    'on' => string,
    'priority' => int,
    'listener' => \West\Event\Listener\ListenerInterface
]
```

If any of the keys are missing a ```West\Event\Exception\InvalidArgumentException``` will be thrown.  No further validation is done on this constructor argument so it is up to the developer to ensure the event manager will not trigger an unexpected error.  


## Breaking an event loop

To prevent execution of subsequent listeners an `Exception\EventException` can be thrown:

```php
namespace West\Event\Listener;

use West\Event\EventInterface;

class ExceptionListener implements ListenerInterface
{
    public function observesContext($context): bool
    {
        return true;
    }

    public function handle(EventInterface $event): bool
    {
        throw new \West\Event\Exception\EventException();
    }
}

namespace West\Event;

$eventName = 'event-name';
$eventManager = new EventManager(
    [
        [
            'on' => $eventName,
            'priority' => 0,
            'listener' => new Listener\ExceptionListener()
        ],
        [
            // listener will never execute
            'on' => $eventName,
            'priority' => 1,
            'listener' => new Listener\FooListener()
        ],
    ]
);
```


## Event context

The event context allowed listeners to execute conditionally:
 
 ```php
namespace West\Event\Context;

class SomeContext
{

}

namespace West\Event\Listener;

use West\Event\EventInterface;
use West\Event\Context\SomeContext;

class BarListener implements ListenerInterface
{
    public function observesContext($context): bool
    {
        return $context instanceof SomeContext;
    }

    public function handle(EventInterface $event): bool
    {
        return 'another-value';
    }
}

namespace West\Event;

$eventName = 'event-name';
$eventManager = new EventManager(
    [
        [
            'on' => $eventName,
            'priority' => 0,
            'listener' => new Listener\BarListener()
        ],
        [
            // not executed in this example
            'on' => $eventName,
            'priority' => 1,
            'listener' => new Listener\FooListener()
        ],
    ]
);

// event context
$context = new Context\SomeContext();

$event = new Event(
    $eventName,
    $context,
    [
        'event-parameter' => 'parameter-value'
    ]
);

// $result === 'another-value'
$result = $eventManager->trigger($event);
 ```

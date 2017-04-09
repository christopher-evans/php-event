# West\\Event

An event registry implementation for PHP.


## Getting started

An event percipient is a class implementing the `Percipient\EventPercipient` interface:
 
```php
namespace West\Event\Percipient;

use West\Event\EventInterface;

class FooPercipient implements PercipientInterface
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

A percipient can be registered with an event registry instance:

```php
$eventName = 'event-name';
$eventRegistry = new EventRegistry(
    [
        [
            'on' => $eventName,
            'priority' => 0,
            'percipient' => new FooPercipient()
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
$result = $eventRegistry->trigger($event);
```

A lower priority percipient is executed earlier than a higher priority percipient. There is no guarantee of the order of execution for two percipients attached with the same priority.


## Events

Event names are case sensitive, alphanumeric strings that may include ```'.', '-', '_'``` at any position.

Event parameters are passed into the constructor as seen above. The parameters can be retrieved in a percipient as an array of all parameters:

```php
$event->getParameters();
```

or individually:


```php
$event->getParameter('parameter-name');
```

The method ```EventInterface::getParameter``` will throw a ```West\Event\Exception\InvalidArgumentException``` if the is no parameter with the provided key. Note that objects passed as event parameters will break immutability of the event, so should be avoided.


## Event registry

The event registry constructor expects an array of arrays of the form:

```php
[
    'on' => string,
    'priority' => int,
    'percipient' => \West\Event\Percipient\PercipientInterface
]
```

If any of the keys are missing or of the wrong type a ```West\Event\Exception\InvalidArgumentException``` will be thrown.  


## Breaking an event loop

To prevent execution of subsequent percipients an `Exception\EventException` can be thrown:

```php
namespace West\Event\Percipient;

use West\Event\EventInterface;

class ExceptionPercipient implements PercipientInterface
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
$eventRegistry = new EventRegistry(
    [
        [
            'on' => $eventName,
            'priority' => 0,
            'percipient' => new Percipient\ExceptionPercipient()
        ],
        [
            // percipient will never execute
            'on' => $eventName,
            'priority' => 1,
            'percipient' => new Percipient\FooPercipient()
        ],
    ]
);
```


## Event context

The event context allowed percipients to execute conditionally:
 
 ```php
namespace West\Event\Context;

class SomeContext
{

}

namespace West\Event\Percipient;

use West\Event\EventInterface;
use West\Event\Context\SomeContext;

class BarPercipient implements PercipientInterface
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
$eventRegistry = new EventRegistry(
    [
        [
            'on' => $eventName,
            'priority' => 0,
            'percipient' => new Percipient\BarPercipient()
        ],
        [
            // not executed in this example
            'on' => $eventName,
            'priority' => 1,
            'percipient' => new Percipient\FooPercipient()
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
$result = $eventRegistry->trigger($event);
 ```

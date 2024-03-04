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

final class Event implements EventInterface
{
    /**
     * Event name
     *
     * @var string
     */
    private $name;

    /**
     * Event context
     *
     * @var object
     */
    private $context;

    /**
     * Event parameters
     *
     * @var array
     */
    private $parameters;

    /**
     * Event name regex for invalid characters
     *
     * @var string
     */
    private static $eventNameRegex = '/[^a-z0-9_\.-]/i';

    /**
     * Event constructor.
     *
     * @param string $name
     * @param object $context
     * @param array $parameters
     */
    public function __construct(string $name, $context, array $parameters = [])
    {
        if (! $this->isValidName($name)) {
            throw new InvalidArgumentException(sprintf('Invalid name: %s', $name));
        }

        $this->name = $name;
        $this->context = $context;
        $this->parameters = $parameters;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function getParameter(string $key)
    {
        if (! array_key_exists($key, $this->parameters)) {
            throw new InvalidArgumentException(sprintf('Invalid parameter: %s', $key));
        }

        return $this->parameters[$key];
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    private function isValidName(string $eventName)
    {
        return ! preg_match(self::$eventNameRegex, $eventName);
    }

}

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
 * Representation of an event
 */
interface EventInterface
{
    /**
     * Get the event name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the context for the event
     *
     * @return object
     */
    public function getContext();

    /**
     * Get the event parameters
     *
     * @return array
     */
    public function getParameters(): array;

    /**
     * Get a single parameter by name
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getParameter(string $name);
}
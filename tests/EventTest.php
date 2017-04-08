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

use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    private $context;

    public function setUp()
    {
        $this->context = new \stdClass();
    }

    public function testInvalidName()
    {
        $this->expectException(InvalidArgumentException::class);

        $invalidName = 'invalid&name';
        new Event($invalidName, $this->context);
    }

    public function testName()
    {
        $name = 'name';
        $event = new Event($name, $this->context);

        $actualName = $event->getName();
        $this->assertEquals($name, $actualName);
    }

    public function testContext()
    {
        $name = 'name';
        $event = new Event($name, $this->context);

        $actualContext = $event->getContext();
        $this->assertEquals($this->context, $actualContext);
    }

    public function testParameter()
    {
        $name = 'name';
        $parameterKey = 'key';
        $parameterValue = 'Value';
        $parameters = [
            $parameterKey => $parameterValue
        ];
        $event = new Event($name, $this->context, $parameters);

        $actualParameter = $event->getParameter($parameterKey);
        $this->assertEquals($parameters[$parameterKey], $actualParameter);
    }

    public function testParameters()
    {
        $name = 'name';
        $parameters = [
            'key' => 'Value'
        ];
        $event = new Event($name, $this->context, $parameters);

        $actualParameters = $event->getParameters();
        $this->assertEquals($parameters, $actualParameters);
    }

    public function testInvalidParameter()
    {
        $this->expectException(InvalidArgumentException::class);

        $event = new Event('name', $this->context);
        $event->getParameter('unset-parameter');
    }
}

<?php

namespace Flame\Test\Grammar;

use Flame\Grammar\Grammar;

class GrammarTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Grammar
     */
    private $grammar;

    protected function setUp()
    {
        $this->grammar = new Grammar();
    }

    public function testBuildId()
    {
        $this->assertSame('"users"', $this->grammar->buildId('users'));
        $this->assertSame('"users" AS "u"', $this->grammar->buildId('users u'));
        $this->assertSame('"users" AS "u"', $this->grammar->buildIdWithAlias('users', 'u'));
    }

    public function testBuildNestedId()
    {
        $this->assertSame('"users"."username"', $this->grammar->buildId('users.username'));
        $this->assertSame('"db"."users"."username"', $this->grammar->buildId('db.users.username'));
        $this->assertSame('"db"."users"."username.foo"', $this->grammar->buildId('db.users.username.foo'));
    }

    public function testBuildDateTime()
    {
        $date = new \DateTime();
        $expected = $date->format(Grammar::DATE_TIME_FORMAT);

        $this->assertEquals($expected, $this->grammar->buildDateTime($date));
        $this->assertEquals($expected, $this->grammar->buildDateTime($date->getTimestamp()));
        $this->assertEquals($expected, $this->grammar->buildDateTime($expected));

        $this->setExpectedException('InvalidArgumentException');

        $this->grammar->buildDateTime([]);
    }

    public function testBuildTime()
    {
        $date = new \DateTime();
        $expected = $date->format(Grammar::TIME_FORMAT);

        $this->assertEquals($expected, $this->grammar->buildTime($date));
        $this->assertEquals($expected, $this->grammar->buildTime($date->getTimestamp()));
        $this->assertEquals($expected, $this->grammar->buildTime($expected));

        $this->setExpectedException('InvalidArgumentException');

        $this->grammar->buildTime([]);
    }
}

<?php

namespace Flame\Test\Grammar;

use Flame\Grammar\Grammar;

class GrammarTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildId()
    {
        $grammar = new Grammar();

        $this->assertSame('"users"', $grammar->buildId('users'));
        $this->assertSame('"users"."username"', $grammar->buildId('users.username'));
        $this->assertSame('"db"."users"."username"', $grammar->buildId('db.users.username'));
        $this->assertSame('"db"."users"."username.foo"', $grammar->buildId('db.users.username.foo'));

        $this->assertSame('"users" AS "u"', $grammar->buildId('users u'));
        $this->assertSame('"users" AS "u"', $grammar->buildIdWithAlias('users', 'u'));
    }

    public function testBuildDateTime()
    {
        $grammar = new Grammar();
        $date = new \DateTime();
        $expected = $date->format(Grammar::DATE_TIME_FORMAT);

        $this->assertEquals($expected, $grammar->buildDateTime($date));
        $this->assertEquals($expected, $grammar->buildDateTime($date->getTimestamp()));
        $this->assertEquals($expected, $grammar->buildDateTime($expected));

        $this->setExpectedException('InvalidArgumentException');

        $grammar->buildDateTime([]);
    }

    public function testBuildTime()
    {
        $grammar = new Grammar();
        $date = new \DateTime();
        $expected = $date->format(Grammar::TIME_FORMAT);

        $this->assertEquals($expected, $grammar->buildTime($date));
        $this->assertEquals($expected, $grammar->buildTime($date->getTimestamp()));
        $this->assertEquals($expected, $grammar->buildTime($expected));

        $this->setExpectedException('InvalidArgumentException');

        $grammar->buildTime([]);
    }
}

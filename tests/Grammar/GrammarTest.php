<?php

namespace Flame\Test\Grammar;

use Flame\Grammar\Grammar;

class GrammarTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildId()
    {
        $grammar = new Grammar();

        $this->assertSame('"users"', $grammar->buildId('users'));
        $this->assertSame('"users" AS "u"', $grammar->buildId('users u'));
        $this->assertSame('"users" AS "u"', $grammar->buildIdWithAlias('users', 'u'));
    }
}

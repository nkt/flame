<?php

namespace Flame\Test\Grammar;

use Flame\Grammar\MysqlGrammar;

class MysqlGrammarTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildId()
    {
        $grammar = new MysqlGrammar();

        $this->assertSame('`users`', $grammar->buildId('users'));
        $this->assertSame('`users` `u`', $grammar->buildId('users u'));
        $this->assertSame('`users` `u`', $grammar->buildIdWithAlias('users', 'u'));
    }
}

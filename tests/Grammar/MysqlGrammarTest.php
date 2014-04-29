<?php

namespace Flame\Test\Grammar;

use Flame\Grammar\MysqlGrammar;

class MysqlGrammarTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildId()
    {
        $grammar = new MysqlGrammar();

        $this->assertSame('`users`', $grammar->buildId('users'));
        $this->assertSame('`users`.`username`', $grammar->buildId('users.username'));
        $this->assertSame('`db`.`users`.`username`', $grammar->buildId('db.users.username'));
        $this->assertSame('`db`.`users`.`username.foo`', $grammar->buildId('db.users.username.foo'));

        $this->assertSame('`users` AS `u`', $grammar->buildId('users u'));
        $this->assertSame('`users` AS `u`', $grammar->buildIdWithAlias('users', 'u'));
    }
}

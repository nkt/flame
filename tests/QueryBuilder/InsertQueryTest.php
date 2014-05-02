<?php

namespace Flame\Test\QueryBuilder;

use Flame\Grammar\Grammar;
use Flame\QueryBuilder\InsertQuery;

class InsertQueryTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialState()
    {
        $insert = new InsertQuery(new Grammar(), 'test', []);

        $this->assertSame('INSERT INTO "test"() VALUES()', (string)$insert);

        $insert = new InsertQuery(new Grammar(), 'test', ['foo' => 'bar']);

        $this->assertSame('INSERT INTO "test"("foo") VALUES(bar)', (string)$insert);
    }

    public function testFrom()
    {
        $insert = new InsertQuery(new Grammar(), 'test', []);
        $insert->table('users');

        $this->assertSame('INSERT INTO "users"() VALUES()', (string)$insert);
    }

    public function testColumns()
    {
        $insert = new InsertQuery(new Grammar(), 'test', []);
        $insert->column('foo', 'bar');

        $this->assertSame('INSERT INTO "test"("foo") VALUES(bar)', (string)$insert);

        $insert->columns(['foo' => 1, 'bar' => 2]);

        $this->assertSame('INSERT INTO "test"("foo", "bar") VALUES(1, 2)', (string)$insert);
    }
}

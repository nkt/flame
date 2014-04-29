<?php

namespace Flame\Test\Query;

use Flame\Grammar\Grammar;
use Flame\QueryBuilder\Expression;
use Flame\QueryBuilder\SelectQuery;

class SelectQueryTest extends \PHPUnit_Framework_TestCase
{
    private function select()
    {
        return new SelectQuery(new Grammar(), []);
    }

    public function testFrom()
    {
        $select = $this->select();
        $this->assertSame($select, $select->from('users'));
        $this->assertSame('SELECT * FROM "users"', (string)$select);
        $this->assertSame('SELECT * FROM "users", "comments" AS "c"', (string)$select->from('comments', 'c'));
    }

    public function testColumnAdding()
    {
        $select = $this->select();
        $this->assertSame($select, $select->from('users')->column('id')->column('username', 'name'));
        $this->assertSame('SELECT "id", "username" AS "name" FROM "users"', (string)$select);
    }

    public function testMultiColumnAdding()
    {
        $select = $this->select();
        $this->assertSame($select, $select->from('users')->columns('id', 'username'));
        $this->assertSame('SELECT "id", "username" FROM "users"', (string)$select);
    }

    public function testDistinct()
    {
        $select = $this->select();
        $this->assertSame($select, $select->from('users')->distinct());
        $this->assertSame('SELECT DISTINCT * FROM "users"', (string)$select);
        $this->assertSame('SELECT * FROM "users"', (string)$select->distinct(false));
    }

    public function testLimit()
    {
        $select = $this->select();
        $this->assertSame($select, $select->from('users')->limit(10));
        $this->assertSame('SELECT * FROM "users" LIMIT 10', (string)$select);

        $this->assertSame($select, $select->offset(100));
        $this->assertSame('SELECT * FROM "users" LIMIT 100, 10', (string)$select);
    }

    public function testGroupBy()
    {
        $select = $this->select();
        $this->assertSame($select, $select->from('users')->groupBy('username'));
        $this->assertSame('SELECT * FROM "users" GROUP BY "username"', (string)$select);
        $this->assertSame('SELECT * FROM "users" GROUP BY "username", "id"', (string)$select->groupBy('id'));
    }

    public function testOrderBy()
    {
        $select = $this->select();
        $this->assertSame($select, $select->from('users')->orderBy('username'));
        $this->assertSame('SELECT * FROM "users" ORDER BY "username" ASC', (string)$select);
        $this->assertSame('SELECT * FROM "users" ORDER BY "username" ASC, "id" DESC', (string)$select->orderBy('id', false));
    }

    public function testBuildOrder()
    {
        $select = $this->select();
        $select->limit(10)->offset(100)->from('users')->groupBy('foo')->orderBy('bar')->column('foo');
        $this->assertSame('SELECT "foo" FROM "users" ORDER BY "bar" ASC GROUP BY "foo" LIMIT 100, 10', (string)$select);
    }

    public function testExpr()
    {
        $select = $this->select();
        $expr = $select->expr();

        $this->assertInstanceOf('Flame\\QueryBuilder\\Expression', $expr);
        $this->assertSame(
            $this->getObjectAttribute($select, 'grammar'),
            $this->getObjectAttribute($expr, 'grammar')
        );
    }

    public function testWhere()
    {
        $select = $this->select();
        $select->from('users')->where($select->expr()->equal('foo', ':bar'));
        $this->assertSame('SELECT * FROM "users" WHERE "foo" = :bar', (string)$select);
    }

    public function testEmptyWhere()
    {
        $select = $this->select();
        $select->from('users')->where($select->expr());
        $this->assertSame('SELECT * FROM "users"', (string)$select);
    }

    public function testWhereWithClosure()
    {
        $select = $this->select();
        $select->from('users')->where(function (Expression $e) {
            $e->equal('foo', ':bar');
        });
        $this->assertSame('SELECT * FROM "users" WHERE "foo" = :bar', (string)$select);
    }

    public function testUnion()
    {
        $select = $this->select()->from('users')->union($this->select()->from('old_users'), true);
        $expected = "SELECT * FROM \"users\"\nUNION ALL\nSELECT * FROM \"old_users\"";
        $this->assertEquals($expected, (string)$select);

        $select->union($this->select()->from('new_users'));
        $this->assertEquals($expected . "\nUNION\nSELECT * FROM \"new_users\"", (string)$select);
    }
}

<?php

namespace Flame\Test\Query;

use Flame\Grammar\Grammar;
use Flame\Query\SelectQuery;

class SelectQueryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SelectQuery
     */
    protected $select;

    protected function setUp()
    {
        $this->select = new SelectQuery(new Grammar(), []);
    }

    public function testFrom()
    {
        $this->assertSame($this->select, $this->select->from('users'));
        $this->assertSame('SELECT * FROM "users"', (string)$this->select);
        $this->assertSame('SELECT * FROM "users", "comments" AS "c"', (string)$this->select->from('comments', 'c'));
    }

    public function testColumnAdding()
    {
        $this->assertSame($this->select, $this->select->from('users')->column('id')->column('username', 'name'));
        $this->assertSame('SELECT "id", "username" AS "name" FROM "users"', (string)$this->select);
    }

    public function testMultiColumnAdding()
    {
        $this->assertSame($this->select, $this->select->from('users')->columns('id', 'username'));
        $this->assertSame('SELECT "id", "username" FROM "users"', (string)$this->select);
    }

    public function testDistinct()
    {
        $this->assertSame($this->select, $this->select->from('users')->distinct());
        $this->assertSame('SELECT DISTINCT * FROM "users"', (string)$this->select);
        $this->assertSame('SELECT * FROM "users"', (string)$this->select->distinct(false));
    }

    public function testLimit()
    {
        $this->assertSame($this->select, $this->select->from('users')->limit(10));
        $this->assertSame('SELECT * FROM "users" LIMIT 10', (string)$this->select);

        $this->assertSame($this->select, $this->select->offset(100));
        $this->assertSame('SELECT * FROM "users" LIMIT 100, 10', (string)$this->select);
    }

    public function testGroupBy()
    {
        $this->assertSame($this->select, $this->select->from('users')->groupBy('username'));
        $this->assertSame('SELECT * FROM "users" GROUP BY "username"', (string)$this->select);
        $this->assertSame('SELECT * FROM "users" GROUP BY "username", "id"', (string)$this->select->groupBy('id'));
    }

    public function testOrderBy()
    {
        $this->assertSame($this->select, $this->select->from('users')->orderBy('username'));
        $this->assertSame('SELECT * FROM "users" ORDER BY "username" ASC', (string)$this->select);
        $this->assertSame('SELECT * FROM "users" ORDER BY "username" ASC, "id" DESC', (string)$this->select->orderBy('id', false));
    }
}

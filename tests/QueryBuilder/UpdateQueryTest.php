<?php

namespace Flame\Test\QueryBuilder;

use Flame\Grammar\Grammar;
use Flame\QueryBuilder\Expression;
use Flame\QueryBuilder\UpdateQuery;

class UpdateQueryTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialState()
    {
        $update = new UpdateQuery(new Grammar(), 'test', []);
        
        $this->assertSame('UPDATE "test" SET ', (string)$update);

        $update = new UpdateQuery(new Grammar(), 'test', ['foo' => 'bar']);

        $this->assertSame('UPDATE "test" SET "foo" = bar', (string)$update);
    }

    public function testTableChange()
    {
        $update = new UpdateQuery(new Grammar(), 'test', []);
        $update->table('users');

        $this->assertSame('UPDATE "users" SET ', (string)$update);
    }

    public function testColumns()
    {
        $update = new UpdateQuery(new Grammar(), 'test', []);
        $update->column('foo', 'bar');

        $this->assertSame('UPDATE "test" SET "foo" = bar', (string)$update);

        $update->columns(['foo' => 1, 'bar' => 2]);

        $this->assertSame('UPDATE "test" SET "foo" = 1, "bar" = 2', (string)$update);
    }

    public function testTop()
    {
        $update = new UpdateQuery(new Grammar(), 'test', ['foo' => 'bar']);
        $update->top(5);

        $this->assertEquals('UPDATE top(5) "test" SET "foo" = bar', (string)$update);
    }

    public function testWhere()
    {
        $update = new UpdateQuery(new Grammar(), 'test', ['foo' => 'bar']);
        $update->where($update->expr()->equal('foo', ':bar'));

        $this->assertEquals('UPDATE "test" SET "foo" = bar WHERE "foo" = :bar', (string)$update);
    }

    public function testEmptyWhere()
    {
        $update = new UpdateQuery(new Grammar(), 'test', ['foo' => 'bar']);
        $update->where($update->expr());

        $this->assertSame('UPDATE "test" SET "foo" = bar', (string)$update);
    }

    public function testWhereWithClosure()
    {
        $update = new UpdateQuery(new Grammar(), 'test', ['foo' => 'bar']);
        $update->where(function (Expression $e) {
            $e->equal('foo', ':bar');
        });

        $this->assertSame('UPDATE "test" SET "foo" = bar WHERE "foo" = :bar', (string)$update);
    }
}

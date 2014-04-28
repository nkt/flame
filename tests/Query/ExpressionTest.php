<?php

namespace Flame\Test\Query;

use Flame\Grammar\Grammar;
use Flame\QueryBuilder\Expression;

class ExpressionTest extends \PHPUnit_Framework_TestCase
{
    protected function expr()
    {
        return new Expression(new Grammar());
    }

    public function testBase()
    {
        $expr = $this->expr();
        $this->assertSame('"foo" = bar', (string)$expr->equal('foo', 'bar'));
        $this->assertSame('"foo" <> bar', (string)$expr->notEqual('foo', 'bar'));
        $this->assertSame('"foo" > bar', (string)$expr->more('foo', 'bar'));
        $this->assertSame('"foo" >= bar', (string)$expr->moreOrEqual('foo', 'bar'));
        $this->assertSame('"foo" < bar', (string)$expr->less('foo', 'bar'));
        $this->assertSame('"foo" <= bar', (string)$expr->lessOrEqual('foo', 'bar'));

        $this->assertSame('"foo" IN(bar)', (string)$expr->in('foo', 'bar'));
        $this->assertSame('"foo" NOT IN(bar)', (string)$expr->notIn('foo', 'bar'));
        $this->assertSame('"foo" BETWEEN 1 AND 10', (string)$expr->between('foo', 1, 10));
        $this->assertSame('"foo" NOT BETWEEN 1 AND 10', (string)$expr->notBetween('foo', 1, 10));

        $this->assertSame('"foo" IS NULL', (string)$expr->isNull('foo'));
        $this->assertSame('"foo" IS NOT NULL', (string)$expr->isNotNull('foo'));

        $this->assertSame('"foo" LIKE %bar%', (string)$expr->like('foo', '%bar%'));
        $this->assertSame('"foo" NOT LIKE %bar%', (string)$expr->notLike('foo', '%bar%'));
    }

    public function testAnd()
    {
        $this->assertSame('AND "foo" = bar', (string)$this->expr()->andEqual('foo', 'bar'));
        $this->assertSame('AND "foo" <> bar', (string)$this->expr()->andNotEqual('foo', 'bar'));
        $this->assertSame('AND "foo" > bar', (string)$this->expr()->andMore('foo', 'bar'));
        $this->assertSame('AND "foo" >= bar', (string)$this->expr()->andMoreOrEqual('foo', 'bar'));
        $this->assertSame('AND "foo" < bar', (string)$this->expr()->andLess('foo', 'bar'));
        $this->assertSame('AND "foo" <= bar', (string)$this->expr()->andLessOrEqual('foo', 'bar'));

        $this->assertSame('AND "foo" IN(bar)', (string)$this->expr()->andIn('foo', 'bar'));
        $this->assertSame('AND "foo" NOT IN(bar)', (string)$this->expr()->andNotIn('foo', 'bar'));
        $this->assertSame('AND "foo" BETWEEN 1 AND 10', (string)$this->expr()->andBetween('foo', 1, 10));
        $this->assertSame('AND "foo" NOT BETWEEN 1 AND 10', (string)$this->expr()->andNotBetween('foo', 1, 10));

        $this->assertSame('AND "foo" IS NULL', (string)$this->expr()->andIsNull('foo'));
        $this->assertSame('AND "foo" IS NOT NULL', (string)$this->expr()->andIsNotNull('foo'));

        $this->assertSame('AND "foo" LIKE %bar%', (string)$this->expr()->andLike('foo', '%bar%'));
        $this->assertSame('AND "foo" NOT LIKE %bar%', (string)$this->expr()->andNotLike('foo', '%bar%'));
    }

    public function testOr()
    {
        $this->assertSame('OR "foo" = bar', (string)$this->expr()->orEqual('foo', 'bar'));
        $this->assertSame('OR "foo" <> bar', (string)$this->expr()->orNotEqual('foo', 'bar'));
        $this->assertSame('OR "foo" > bar', (string)$this->expr()->orMore('foo', 'bar'));
        $this->assertSame('OR "foo" >= bar', (string)$this->expr()->orMoreOrEqual('foo', 'bar'));
        $this->assertSame('OR "foo" < bar', (string)$this->expr()->orLess('foo', 'bar'));
        $this->assertSame('OR "foo" <= bar', (string)$this->expr()->orLessOrEqual('foo', 'bar'));

        $this->assertSame('OR "foo" IN(bar)', (string)$this->expr()->orIn('foo', 'bar'));
        $this->assertSame('OR "foo" NOT IN(bar)', (string)$this->expr()->orNotIn('foo', 'bar'));
        $this->assertSame('OR "foo" BETWEEN 1 AND 10', (string)$this->expr()->orBetween('foo', 1, 10));
        $this->assertSame('OR "foo" NOT BETWEEN 1 AND 10', (string)$this->expr()->orNotBetween('foo', 1, 10));

        $this->assertSame('OR "foo" IS NULL', (string)$this->expr()->orIsNull('foo'));
        $this->assertSame('OR "foo" IS NOT NULL', (string)$this->expr()->orIsNotNull('foo'));

        $this->assertSame('OR "foo" LIKE %bar%', (string)$this->expr()->orLike('foo', '%bar%'));
        $this->assertSame('OR "foo" NOT LIKE %bar%', (string)$this->expr()->orNotLike('foo', '%bar%'));
    }

    public function testComplex()
    {
        $expr = $this->expr()->equal('foo', ':bar')->andIn('id', ':ids')->orBetween('date', ':start', ':end');
        $this->assertSame('"foo" = :bar AND "id" IN(:ids) OR "date" BETWEEN :start AND :end', (string)$expr);
    }

    public function testSubExpression()
    {
        $expr = $this->expr()
            ->equal('foo', ':bar')
            ->orExpression($this->expr()
                ->equal('bar', ':baz')
                ->andExpression($this->expr()->more('age', ':age')));
        $this->assertSame('"foo" = :bar OR ("bar" = :baz AND ("age" > :age))', (string)$expr);
    }
}

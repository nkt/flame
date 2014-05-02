<?php

namespace Flame\Test\QueryBuilder\Part;

use Flame\Grammar\Grammar;
use Flame\QueryBuilder\Expression;

class WherePartTest extends \PHPUnit_Framework_TestCase
{
    private function getGrammar()
    {
        return new Grammar();
    }

    /**
     * @return \Flame\QueryBuilder\Part\WherePart|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getWhere()
    {
        $mock = $this->getMockForTrait('Flame\\QueryBuilder\\Part\\WherePart');
        $mock->expects($this->any())->method('getGrammar')->willReturn($this->getGrammar());

        return $mock;
    }

    public function testWhereWithStatement()
    {
        $where = $this->getWhere();
        $expr = new Expression($this->getGrammar());
        $where->where($expr);

        $this->assertAttributeSame($expr, 'where', $where);
    }

    public function testWhereWithClosure()
    {
        $where = $this->getWhere();
        $where->where(function (Expression $e) {
            $e->equal('foo', 'bar');
        });

        $this->assertAttributeEquals(new Expression($this->getGrammar(), ['foo' => 'bar']), 'where', $where);
    }

    public function testWhereWithArray()
    {
        $where = $this->getWhere();
        $where->where(['foo' => 'bar']);

        $this->assertAttributeEquals(new Expression($this->getGrammar(), ['foo' => 'bar']), 'where', $where);
    }

    public function testWhereException()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->getWhere()->where(1);
    }
}

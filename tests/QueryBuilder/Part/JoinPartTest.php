<?php

namespace Flame\Test\QueryBuilder\Part;

use Flame\Grammar\Grammar;
use Flame\QueryBuilder\Expression;

class JoinPartTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \Flame\QueryBuilder\Part\JoinPart|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getJoin()
    {
        $mock = $this->getMockForTrait('Flame\\QueryBuilder\\Part\\JoinPart');
        $mock->expects($this->any())->method('getGrammar')->willReturn(new Grammar());

        return $mock;
    }

    public function testJoin()
    {
        $join = $this->getJoin();
        $join->join('users u', 'u.id', 'c.user_id');

        $this->assertEquals('INNER JOIN "users" AS "u" ON "u"."id" = "c"."user_id"', $this->getObjectAttribute($join, 'joins')[0]);

        $join->join('users u', function (Expression $e) {
            $e->in('u.id', ':ids');
        });

        $this->assertEquals('INNER JOIN "users" AS "u" ON "u"."id" IN(:ids)', $this->getObjectAttribute($join, 'joins')[1]);

        $join->join('users', 'users.id = :id');

        $this->assertEquals('INNER JOIN "users" ON users.id = :id', $this->getObjectAttribute($join, 'joins')[2]);
    }

    public function testLeftJoin()
    {
        $join = $this->getJoin();
        $join->leftJoin('users u', 'u.id', 'c.user_id');

        $this->assertEquals('LEFT JOIN "users" AS "u" ON "u"."id" = "c"."user_id"', $this->getObjectAttribute($join, 'joins')[0]);
    }

    public function testRightJoin()
    {
        $join = $this->getJoin();
        $join->rightJoin('users u', 'u.id', 'c.user_id');

        $this->assertEquals('RIGHT JOIN "users" AS "u" ON "u"."id" = "c"."user_id"', $this->getObjectAttribute($join, 'joins')[0]);
    }

    public function testFullJoin()
    {
        $join = $this->getJoin();
        $join->fullJoin('users u', 'u.id', 'c.user_id');

        $this->assertEquals('FULL JOIN "users" AS "u" ON "u"."id" = "c"."user_id"', $this->getObjectAttribute($join, 'joins')[0]);
    }

    public function testCrossJoin()
    {
        $join = $this->getJoin();
        $join->crossJoin('users u', 'u.id', 'c.user_id');

        $this->assertEquals('CROSS JOIN "users" AS "u" ON "u"."id" = "c"."user_id"', $this->getObjectAttribute($join, 'joins')[0]);
    }
}

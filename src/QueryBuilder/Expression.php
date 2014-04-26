<?php

namespace Flame\QueryBuilder;

use Flame\Grammar\Grammar;

class Expression
{
    /**
     * @var array
     */
    protected $conditions = [];
    /**
     * @var Grammar
     */
    protected $grammar;

    public function __construct(Grammar $grammar)
    {
        $this->grammar = $grammar;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function equal($column, $argument)
    {
        $this->conditions = [$this->grammar->buildId($column) . ' = ' . $argument];

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function notEqual($column, $argument)
    {
        $this->conditions = [$this->grammar->buildId($column) . ' <> ' . $argument];

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function less($column, $argument)
    {
        $this->conditions = [$this->grammar->buildId($column) . ' < ' . $argument];

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function lessOrEqual($column, $argument)
    {
        $this->conditions = [$this->grammar->buildId($column) . ' <= ' . $argument];

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function more($column, $argument)
    {
        $this->conditions = [$this->grammar->buildId($column) . ' > ' . $argument];

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function moreOrEqual($column, $argument)
    {
        $this->conditions = [$this->grammar->buildId($column) . ' >= ' . $argument];

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function in($column, $argument)
    {
        $this->conditions = [$this->grammar->buildId($column) . ' IN (' . $argument . ')'];

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function notIn($column, $argument)
    {
        $this->conditions = [$this->grammar->buildId($column) . ' NOT IN (' . $argument . ')'];

        return $this;
    }

    /**
     * @param string $column
     * @param string $left
     * @param string $right
     *
     * @return static
     */
    public function between($column, $left, $right)
    {
        $this->conditions = [$this->grammar->buildId($column) . ' BETWEEN ' . $left . ' AND ' . $right];

        return $this;
    }

    /**
     * @param string $column
     * @param string $left
     * @param string $right
     *
     * @return static
     */
    public function notBetween($column, $left, $right)
    {
        $this->conditions = [$this->grammar->buildId($column) . ' NOT BETWEEN ' . $left . ' AND ' . $right];

        return $this;
    }

    /**
     * @param string $column
     *
     * @return static
     */
    public function isNull($column)
    {
        $this->conditions = [$this->grammar->buildId($column) . ' IS NULL'];

        return $this;
    }

    /**
     * @param string $column
     *
     * @return static
     */
    public function isNotNull($column)
    {
        $this->conditions = [$this->grammar->buildId($column) . ' IS NOT NULL'];

        return $this;
    }

    /**
     * @param string $column
     * @param string $pattern
     *
     * @return static
     */
    public function like($column, $pattern)
    {
        $this->conditions = [$this->grammar->buildId($column) . ' LIKE ' . $pattern];

        return $this;
    }

    /**
     * @param string $column
     * @param string $pattern
     *
     * @return static
     */
    public function notLike($column, $pattern)
    {
        $this->conditions = [$this->grammar->buildId($column) . ' NOT LIKE ' . $pattern];

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function andEqual($column, $argument)
    {
        $this->conditions[] = 'AND ' . $this->grammar->buildId($column) . ' = ' . $argument;

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function andNotEqual($column, $argument)
    {
        $this->conditions[] = 'AND ' . $this->grammar->buildId($column) . ' <> ' . $argument;

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function andLess($column, $argument)
    {
        $this->conditions[] = 'AND ' . $this->grammar->buildId($column) . ' < ' . $argument;

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function andLessOrEqual($column, $argument)
    {
        $this->conditions[] = 'AND ' . $this->grammar->buildId($column) . ' <= ' . $argument;

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function andMore($column, $argument)
    {
        $this->conditions[] = 'AND ' . $this->grammar->buildId($column) . ' > ' . $argument;

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function andMoreOrEqual($column, $argument)
    {
        $this->conditions[] = 'AND ' . $this->grammar->buildId($column) . ' >= ' . $argument;

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function andIn($column, $argument)
    {
        $this->conditions[] = 'AND ' . $this->grammar->buildId($column) . ' IN (' . $argument . ')';

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function andNotIn($column, $argument)
    {
        $this->conditions[] = 'AND ' . $this->grammar->buildId($column) . ' NOT IN (' . $argument . ')';

        return $this;
    }

    /**
     * @param string $column
     * @param string $left
     * @param string $right
     *
     * @return static
     */
    public function andBetween($column, $left, $right)
    {
        $this->conditions[] = 'AND ' . $this->grammar->buildId($column) . ' BETWEEN ' . $left . ' AND ' . $right;

        return $this;
    }

    /**
     * @param string $column
     * @param string $left
     * @param string $right
     *
     * @return static
     */
    public function andNotBetween($column, $left, $right)
    {
        $this->conditions[] = 'AND ' . $this->grammar->buildId($column) . ' NOT BETWEEN ' . $left . ' AND ' . $right;

        return $this;
    }

    /**
     * @param string $column
     *
     * @return static
     */
    public function andIsNull($column)
    {
        $this->conditions[] = 'AND ' . $this->grammar->buildId($column) . ' IS NULL';

        return $this;
    }

    /**
     * @param string $column
     *
     * @return static
     */
    public function andIsNotNull($column)
    {
        $this->conditions[] = 'AND ' . $this->grammar->buildId($column) . ' IS NOT NULL';

        return $this;
    }

    /**
     * @param string $column
     * @param string $pattern
     *
     * @return static
     */
    public function andLike($column, $pattern)
    {
        $this->conditions[] = 'AND ' . $this->grammar->buildId($column) . ' LIKE ' . $pattern;

        return $this;
    }

    /**
     * @param string $column
     * @param string $pattern
     *
     * @return static
     */
    public function andNotLike($column, $pattern)
    {
        $this->conditions[] = 'AND ' . $this->grammar->buildId($column) . ' NOT LIKE ' . $pattern;

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function orEqual($column, $argument)
    {
        $this->conditions[] = 'OR ' . $this->grammar->buildId($column) . ' = ' . $argument;

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function orNotEqual($column, $argument)
    {
        $this->conditions[] = 'OR ' . $this->grammar->buildId($column) . ' <> ' . $argument;

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function orLess($column, $argument)
    {
        $this->conditions[] = 'OR ' . $this->grammar->buildId($column) . ' < ' . $argument;

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function orLessOrEqual($column, $argument)
    {
        $this->conditions[] = 'OR ' . $this->grammar->buildId($column) . ' <= ' . $argument;

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function orMore($column, $argument)
    {
        $this->conditions[] = 'OR ' . $this->grammar->buildId($column) . ' > ' . $argument;

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function orMoreOrEqual($column, $argument)
    {
        $this->conditions[] = 'OR ' . $this->grammar->buildId($column) . ' >= ' . $argument;

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function orIn($column, $argument)
    {
        $this->conditions[] = 'OR ' . $this->grammar->buildId($column) . ' IN (' . $argument . ')';

        return $this;
    }

    /**
     * @param string $column
     * @param string $argument
     *
     * @return static
     */
    public function orNotIn($column, $argument)
    {
        $this->conditions[] = 'OR ' . $this->grammar->buildId($column) . ' NOT IN (' . $argument . ')';

        return $this;
    }

    /**
     * @param string $column
     * @param string $left
     * @param string $right
     *
     * @return static
     */
    public function orBetween($column, $left, $right)
    {
        $this->conditions[] = 'OR ' . $this->grammar->buildId($column) . ' BETWEEN ' . $left . ' OR ' . $right;

        return $this;
    }

    /**
     * @param string $column
     * @param string $left
     * @param string $right
     *
     * @return static
     */
    public function orNotBetween($column, $left, $right)
    {
        $this->conditions[] = 'OR ' . $this->grammar->buildId($column) . ' NOT BETWEEN ' . $left . ' OR ' . $right;

        return $this;
    }

    /**
     * @param string $column
     *
     * @return static
     */
    public function orIsNull($column)
    {
        $this->conditions[] = 'OR ' . $this->grammar->buildId($column) . ' IS NULL';

        return $this;
    }

    /**
     * @param string $column
     *
     * @return static
     */
    public function orIsNotNull($column)
    {
        $this->conditions[] = 'OR ' . $this->grammar->buildId($column) . ' IS NOT NULL';

        return $this;
    }

    /**
     * @param string $column
     * @param string $pattern
     *
     * @return static
     */
    public function orLike($column, $pattern)
    {
        $this->conditions[] = 'OR ' . $this->grammar->buildId($column) . ' LIKE ' . $pattern;

        return $this;
    }

    /**
     * @param string $column
     * @param string $pattern
     *
     * @return static
     */
    public function orNotLike($column, $pattern)
    {
        $this->conditions[] = 'OR ' . $this->grammar->buildId($column) . ' NOT LIKE ' . $pattern;

        return $this;
    }

    /**
     * @param Expression $expr
     *
     * @return static
     */
    public function andExpression(Expression $expr)
    {
        $this->conditions[] = 'AND (' . $expr . ')';

        return $this;
    }

    /**
     * @param Expression $expr
     *
     * @return static
     */
    public function orExpression(Expression $expr)
    {
        $this->conditions[] = 'OR (' . $expr . ')';

        return $this;
    }

    public function __toString()
    {
        return join(' ', $this->conditions);
    }
}

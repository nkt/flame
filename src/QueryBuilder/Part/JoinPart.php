<?php

namespace Flame\QueryBuilder\Part;

use Flame\QueryBuilder\Expression;

/**
 * Adds join query part
 * @author Gusakov Nikita <dev@nkt.me>
 */
trait JoinPart
{
    /**
     * @var array
     */
    protected $joins = [];

    /**
     * @param string                     $table
     * @param Expression|callable|string $on
     * @param string|null                $joinField
     * @param string                     $type
     *
     * @return static
     */
    public function join($table, $on, $joinField = null, $type = 'INNER')
    {
        $grammar = $this->getGrammar();
        $join = $type . ' JOIN ' . $grammar->buildId($table) . ' ON ';
        if ($joinField !== null) {
            $join .= $grammar->buildId($on) . ' = ' . $grammar->buildId($joinField);
        } elseif ($on instanceof \Closure) {
            $expr = new Expression($this->getGrammar());
            call_user_func($on, $expr);
            $join .= $expr;
        } else {
            $join .= $on;
        }
        $this->joins[] = $join;

        return $this;
    }

    /**
     * @param string                     $table
     * @param Expression|callable|string $on
     * @param string|null                $joinField
     *
     * @return static
     */
    public function leftJoin($table, $on, $joinField = null)
    {
        return $this->join($table, $on, $joinField, 'LEFT');
    }

    /**
     * @param string                     $table
     * @param Expression|callable|string $on
     * @param string|null                $joinField
     *
     * @return static
     */
    public function rightJoin($table, $on, $joinField = null)
    {
        return $this->join($table, $on, $joinField, 'RIGHT');
    }

    /**
     * @param string                     $table
     * @param Expression|callable|string $on
     * @param string|null                $joinField
     *
     * @return static
     */
    public function fullJoin($table, $on, $joinField = null)
    {
        return $this->join($table, $on, $joinField, 'FULL');
    }

    /**
     * @param string                     $table
     * @param Expression|callable|string $on
     * @param string|null                $joinField
     *
     * @return static
     */
    public function crossJoin($table, $on, $joinField = null)
    {
        return $this->join($table, $on, $joinField, 'CROSS');
    }

    /**
     * @return \Flame\Grammar\Grammar
     */
    abstract protected function getGrammar();
}

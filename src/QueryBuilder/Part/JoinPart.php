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
     * @param string                     $type
     * @param string                     $table
     * @param Expression|callable|string $on
     *
     * @return static
     */
    public function join($type, $table, $on)
    {
        $grammar = $this->getGrammar();
        $join = $type . ' JOIN ' . $grammar->buildId($table) . ' ON ';
        if ($on instanceof Expression) {
            $join .= $on;
        } elseif ($on instanceof \Closure) {
            $expr = new Expression($this->getGrammar());
            call_user_func($on, $expr);
            $join .= $expr;
        }
        $this->joins[] = $join;

        return $this;
    }

    /**
     * @param string                     $table
     * @param Expression|callable|string $on
     *
     * @return static
     */
    public function innerJoin($table, $on)
    {
        return $this->join('INNER', $table, $on);
    }

    /**
     * @param string                     $table
     * @param Expression|callable|string $on
     *
     * @return static
     */
    public function leftJoin($table, $on)
    {
        return $this->join('LEFT', $table, $on);
    }

    /**
     * @param string                     $table
     * @param Expression|callable|string $on
     *
     * @return static
     */
    public function rightJoin($table, $on)
    {
        return $this->join('RIGHT', $table, $on);
    }

    /**
     * @param string                     $table
     * @param Expression|callable|string $on
     *
     * @return static
     */
    public function fullJoin($table, $on)
    {
        return $this->join('FULL', $table, $on);
    }

    /**
     * @param string                     $table
     * @param Expression|callable|string $on
     *
     * @return static
     */
    public function crossJoin($table, $on)
    {
        return $this->join('CROSS', $table, $on);
    }

    /**
     * @return \Flame\Grammar\Grammar
     */
    abstract protected function getGrammar();
}

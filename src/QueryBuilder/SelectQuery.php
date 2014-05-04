<?php

namespace Flame\QueryBuilder;

use Flame\Grammar\Grammar;
use Flame\QueryBuilder\Part\JoinPart;
use Flame\QueryBuilder\Part\WherePart;

/**
 * Select query
 * @author Gusakov Nikita <dev@nkt.me>
 */
class SelectQuery
{
    use WherePart;
    use JoinPart;

    /**
     * @var Grammar
     */
    protected $grammar;
    /**
     * @var array
     */
    protected $columns;
    /**
     * @var array
     */
    protected $from = [];
    /**
     * @var bool
     */
    protected $distinct = false;
    /**
     * @var array
     */
    protected $groups = [];
    /**
     * @var array
     */
    protected $orders = [];
    /**
     * @var string|int
     */
    protected $offset;
    /**
     * @var string|int
     */
    protected $limit;
    /**
     * @var SelectQuery[]
     */
    protected $unions = [];

    /**
     * @param Grammar $grammar
     * @param array   $columns
     */
    public function __construct(Grammar $grammar, array $columns)
    {
        $this->grammar = $grammar;
        $this->columns = $columns;
    }

    /**
     * @param string $table
     * @param string $alias
     *
     * @return static
     */
    public function from($table, $alias = null)
    {
        if ($alias === null) {
            $this->from[] = $this->grammar->buildId($table);
        } else {
            $this->from[] = $this->grammar->buildIdWithAlias($table, $alias);
        }

        return $this;
    }

    /**
     * @param string $name
     * @param string $alias
     *
     * @return static
     */
    public function column($name, $alias = null)
    {
        if ($alias === null) {
            $this->columns[] = $this->grammar->buildId($name);
        } else {
            $this->columns[] = $this->grammar->buildIdWithAlias($name, $alias);
        }

        return $this;
    }

    /**
     * @param string $column,...
     *
     * @return static
     */
    public function columns($column)
    {
        foreach (func_get_args() as $column) {
            $this->columns[] = $this->grammar->buildId($column);
        }

        return $this;
    }

    /**
     * @param bool $distinct
     *
     * @return static
     */
    public function distinct($distinct = true)
    {
        $this->distinct = (bool)$distinct;

        return $this;
    }

    /**
     * @param string $column
     *
     * @return static
     */
    public function groupBy($column)
    {
        $this->groups[] = $this->grammar->buildId($column);

        return $this;
    }

    /**
     * @param string $column
     * @param bool   $asc
     *
     * @return static
     */
    public function orderBy($column, $asc = true)
    {
        if ($asc) {
            $this->orders[] = $this->grammar->buildId($column) . ' ASC';
        } else {
            $this->orders[] = $this->grammar->buildId($column) . ' DESC';
        }

        return $this;
    }

    /**
     * @param string|int $max
     *
     * @return static
     */
    public function limit($max)
    {
        $this->limit = $max;

        return $this;
    }

    /**
     * @param string|int $offset
     *
     * @return static
     */
    public function offset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @param SelectQuery $select
     * @param bool        $all
     *
     * @return static
     */
    public function union(SelectQuery $select, $all = false)
    {
        $this->unions[] = "\nUNION" . ($all ? ' ALL' : '') . "\n" . $select;

        return $this;
    }

    /**
     * @return Expression
     */
    public function expr()
    {
        return new Expression($this->grammar);
    }

    /**
     * {@inheritdoc}
     */
    protected function getGrammar()
    {
        return $this->grammar;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $sql = $this->distinct ? 'SELECT DISTINCT ' : 'SELECT ';

        if (empty($this->columns)) {
            $sql .= '*';
        } else {
            $sql .= join(', ', $this->columns);
        }

        $sql .= ' FROM ' . join(', ', $this->from);

        if (!empty($this->joins)) {
            $sql .= ' ' . join(' ', $this->joins);
        }

        if ('' !== $where = (string)$this->where) {
            $sql .= ' WHERE ' . $where;
        }

        if (!empty($this->orders)) {
            $sql .= ' ORDER BY ' . join(', ', $this->orders);
        }

        if (!empty($this->groups)) {
            $sql .= ' GROUP BY ' . join(', ', $this->groups);
        }

        if ($this->limit !== null) {
            if ($this->offset !== null) {
                $sql .= ' LIMIT ' . $this->offset . ', ' . $this->limit;
            } else {
                $sql .= ' LIMIT ' . $this->limit;
            }
        }
        if (!empty($this->unions)) {
            $sql .= join('', $this->unions);
        }

        return $sql;
    }
}

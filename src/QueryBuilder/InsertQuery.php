<?php

namespace Flame\QueryBuilder;

use Flame\Grammar\Grammar;

/**
 * Insert query
 * @author Gusakov Nikita <dev@nkt.me>
 */
class InsertQuery
{
    /**
     * @var array
     */
    protected $columns = [];
    /**
     * @var Grammar
     */
    protected $grammar;
    /**
     * @var string
     */
    protected $table;

    /**
     * @param Grammar $grammar
     * @param string  $table
     * @param array   $columns
     */
    public function __construct(Grammar $grammar, $table, array $columns)
    {
        $this->grammar = $grammar;
        $this->table = $table;
        $this->columns($columns);
    }

    /**
     * @param string $table
     *
     * @return static
     */
    public function into($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return static
     */
    public function column($name, $value)
    {
        $this->columns[$this->grammar->buildId($name)] = $value;

        return $this;
    }

    /**
     * @param array $columns
     *
     * @return static
     */
    public function columns(array $columns)
    {
        foreach ($columns as $name => $value) {
            $this->columns[$this->grammar->buildId($name)] = $value;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'INSERT INTO ' . $this->grammar->buildId($this->table)
        . '(' . join(', ', array_keys($this->columns)) . ') VALUES('
        . join(', ', array_values($this->columns)) . ')';
    }
} 

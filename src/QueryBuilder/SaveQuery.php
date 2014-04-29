<?php

namespace Flame\QueryBuilder;

use Flame\Grammar\Grammar;

/**
 * Base class for save queries
 * @author Gusakov Nikita <dev@nkt.me>
 */
abstract class SaveQuery
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
     * @param string $name
     *
     * @return static
     */
    public function table($name)
    {
        $this->table = $name;

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
    abstract function __toString();
}

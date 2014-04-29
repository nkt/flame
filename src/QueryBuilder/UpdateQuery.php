<?php

namespace Flame\QueryBuilder;

use Flame\QueryBuilder\Part\WherePart;

/**
 * Update query
 * @author Gusakov Nikita <dev@nkt.me>
 */
class UpdateQuery extends SaveQuery
{
    use WherePart;

    /**
     * @var string|int
     */
    protected $top;

    /**
     * {@inheritdoc}
     */
    public function expr()
    {
        return new Expression($this->grammar);
    }

    /**
     * @param string|int $max
     */
    public function top($max)
    {
        $this->top = $max;
    }

    /**
     * {@inheritdoc}
     */
    protected function getGrammar()
    {
        return $this->grammar;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $set = [];
        foreach ($this->columns as $name => $value) {
            $set[] = $name . ' = ' . $value;
        }
        $sql = 'UPDATE ';
        if ($this->top !== null) {
            $sql .= 'top(' . $this->top . ') ';
        }
        $sql .= $this->grammar->buildId($this->table) . ' SET ' . join(', ', $set);
        if ('' !== $where = (string)$this->where) {
            $sql .= ' WHERE ' . $this->where;
        }

        return $sql;
    }
} 

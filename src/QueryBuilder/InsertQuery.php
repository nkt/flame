<?php

namespace Flame\QueryBuilder;

/**
 * Insert query
 * @author Gusakov Nikita <dev@nkt.me>
 */
class InsertQuery extends SaveQuery
{
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

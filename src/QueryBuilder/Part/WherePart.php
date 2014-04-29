<?php

namespace Flame\QueryBuilder\Part;

use Flame\QueryBuilder\Expression;

/**
 * Adds where query part
 * @author Gusakov Nikita <dev@nkt.me>
 */
trait WherePart
{
    /**
     * @var Expression
     */
    protected $where;

    /**
     * @param Expression|callable $stmt
     *
     * @return static
     */
    public function where($stmt)
    {
        if ($stmt instanceof Expression) {
            $this->where = $stmt;
        } elseif ($stmt instanceof \Closure) {
            $this->where = $this->expr();
            call_user_func($stmt, $this->where);
        }

        return $this;
    }

    /**
     * @return Expression
     */
    abstract public function expr();
} 

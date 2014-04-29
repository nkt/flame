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
            $this->where = new Expression($this->getGrammar());
            call_user_func($stmt, $this->where);
        }

        return $this;
    }

    /**
     * @return \Flame\Grammar\Grammar
     */
    abstract protected function getGrammar();
} 

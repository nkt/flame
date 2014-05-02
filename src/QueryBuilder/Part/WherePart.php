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
     * @param Expression|callable|array $stmt
     *
     * @return static
     * @throws \InvalidArgumentException
     */
    public function where($stmt)
    {
        if ($stmt instanceof Expression) {
            $this->where = $stmt;
        } elseif ($stmt instanceof \Closure) {
            $this->where = new Expression($this->getGrammar());
            call_user_func($stmt, $this->where);
        } elseif (is_array($stmt)) {
            $this->where = new Expression($this->getGrammar(), $stmt);
        } else {
            throw new \InvalidArgumentException('Where statement could to be Expression, callable or array');
        }

        return $this;
    }

    /**
     * @return \Flame\Grammar\Grammar
     */
    abstract protected function getGrammar();
} 

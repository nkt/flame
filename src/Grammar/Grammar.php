<?php

namespace Flame\Grammar;

/**
 * Grammar
 * @author Gusakov Nikita <dev@nkt.me>
 */
class Grammar
{
    public function buildId($id)
    {
        $parts = explode(' ', $id, 2);
        if (count($parts) === 2) {
            return $this->buildIdWithAlias($parts[0], $parts[1]);
        }

        return $this->wrap($id);
    }

    public function buildIdWithAlias($id, $alias)
    {
        return $this->wrap($id) . ' AS ' . $this->wrap($alias);
    }

    protected function wrap($id)
    {
        return '"' . $id . '"';
    }
} 

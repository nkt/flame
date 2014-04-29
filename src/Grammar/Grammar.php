<?php

namespace Flame\Grammar;

/**
 * Grammar
 * @author Gusakov Nikita <dev@nkt.me>
 */
class Grammar
{
    const QUOTE_CHAR = '"';

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
        $parts = explode('.', $id, 3);

        return join('.', array_map(function ($id) {
            return static::QUOTE_CHAR . $id . static::QUOTE_CHAR;
        }, $parts));
    }
} 

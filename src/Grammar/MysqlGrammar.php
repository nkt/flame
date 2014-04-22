<?php

namespace Flame\Grammar;

class MysqlGrammar extends Grammar
{
    public function buildIdWithAlias($id, $alias)
    {
        return $this->wrap($id) . ' ' . $this->wrap($alias);
    }

    protected function wrap($id)
    {
        return '`' . $id . '`';
    }
} 

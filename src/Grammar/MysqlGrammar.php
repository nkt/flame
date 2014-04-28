<?php

namespace Flame\Grammar;

/**
 * Mysql grammar
 * @author Gusakov Nikita <dev@nkt.me>
 */
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

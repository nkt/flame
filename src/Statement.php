<?php

namespace Flame;

/**
 * Statement
 * @author Gusakov Nikita <dev@nkt.me>
 */
class Statement extends \PDOStatement
{
    /**
     * @param array $parameters
     *
     * @return static
     * @throws Exception
     */
    public function execute($parameters = null)
    {
        if (!parent::execute($parameters)) {
            throw new Exception();
        }

        return $this;
    }

    /**
     * @param callable $callback
     * @param int      $mode
     *
     * @return array
     */
    public function fetchCallback(callable $callback, $mode = \PDO::FETCH_ASSOC)
    {
        $results = [];
        while (false !== $row = $this->fetch($mode)) {
            $results[] = call_user_func($callback, $row);
        }

        return $results;
    }

    /**
     * @see execute
     */
    public function __invoke(array $parameters = [])
    {
        return $this->execute($parameters);
    }

    /**
     * @return string The query string
     */
    public function __toString()
    {
        return $this->queryString;
    }
}

<?php

namespace Flame;

/**
 * Statement
 * @author Gusakov Nikita <dev@nkt.me>
 */
class Statement extends \PDOStatement
{
    /**
     * @var array
     */
    private $placeholders;
    /**
     * @var array
     */
    private $types;

    private function __construct($placeholders, $types)
    {
        $this->placeholders = $placeholders;
        $this->types = $types;
    }

    /**
     * @param array $parameters
     *
     * @return static
     * @throws Exception
     */
    public function execute($parameters = null)
    {
        if ($parameters !== null) {
            $parameters = array_intersect_key($parameters, $this->types);
            foreach ($this->placeholders as $i => $name) {
                $value = & $parameters[$name];
                if ($value === null) {
                    $this->bindValue($i + 1, null, \PDO::PARAM_NULL);
                } else {
                    $type = & $this->types[$name];
                    if ($type === \PDO::PARAM_INT) {
                        $value = (int)$value;
                    }
                    $this->bindValue($i + 1, $value, $type);
                }
            }
        }
        if (!parent::execute()) {
            throw new Exception();
        }

        return $this;
    }

    /**
     * @return static
     * @throws Exception
     */
    public function closeCursor()
    {
        if (!parent::closeCursor()) {
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

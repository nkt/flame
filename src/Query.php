<?php

namespace Flame;

use Flame\Grammar\Grammar;

/**
 * Query
 * @author Gusakov Nikita <dev@nkt.me>
 */
class Query extends \PDOStatement
{
    /**
     * @var array
     */
    protected $placeholders;
    /**
     * @var array
     */
    protected $types;
    /**
     * @var Grammar
     */
    protected $grammar;

    private function __construct(Grammar $grammar, $placeholders, $types)
    {
        $this->grammar = $grammar;
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
                    $this->bindValue($i + 1, null, Connection::PARAM_NULL);
                } else {
                    $type = & $this->types[$name];
                    if ($type === Connection::PARAM_INT) {
                        $value = (int)$value;
                    } elseif ($type === Connection::PARAM_DATE_TIME) {
                        $value = $this->grammar->buildDateTime($value);
                        $type = Connection::PARAM_STR;
                    } elseif ($type === Connection::PARAM_TIME) {
                        $value = $this->grammar->buildTime($value);
                        $type = Connection::PARAM_STR;
                    }
                    $this->bindValue($i + 1, $value, $type);
                }
            }
        }
        parent::execute();

        return $this;
    }

    /**
     * @return static
     * @throws Exception
     */
    public function closeCursor()
    {
        parent::closeCursor();

        return $this;
    }

    /**
     * @param callable $callback
     * @param int      $mode
     *
     * @return array
     */
    public function fetchCallback(callable $callback, $mode = null)
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

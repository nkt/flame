<?php

namespace Flame;

use Flame\Grammar\Grammar;
use Flame\QueryBuilder\SelectQuery;

/**
 * Flame
 * @author Gusakov Nikita <dev@nkt.me>
 */
class Connection extends \PDO
{
    const PLACEHOLDER_REGEX = '~([sbnilf]{0,1}):(\w+)~';
    protected static $typeMap = [
        ''  => \PDO::PARAM_STR, // string by default
        's' => \PDO::PARAM_STR,
        'i' => \PDO::PARAM_INT,
        'f' => \PDO::PARAM_STR,
        'b' => \PDO::PARAM_BOOL,
        'n' => \PDO::PARAM_NULL,
        'l' => \PDO::PARAM_LOB
    ];

    /**
     * @var array
     */
    private $placeholders;
    /**
     * @var array
     */
    private $types;
    /**
     * @var Grammar
     */
    protected $grammar;

    /**
     * @param string  $dsn
     * @param string  $username
     * @param string  $password
     * @param array   $attributes
     * @param Grammar $grammar
     */
    public function __construct($dsn, $username = null, $password = null, array $attributes = [], Grammar $grammar = null)
    {
        parent::__construct($dsn, $username, $password, array_replace($attributes, [
            \PDO::ATTR_ERRMODE         => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_STATEMENT_CLASS => ['Flame\\Query', [&$this->placeholders, &$this->types]]
        ]));

        if ($grammar === null) {
            $this->grammar = new Grammar();
        } else {
            $this->grammar = $grammar;
        }
    }

    /**
     * @param string $sql
     * @param array  $driverOptions
     *
     * @return Query
     */
    public function prepare($sql, $driverOptions = [])
    {
        $this->placeholders = $this->types = [];
        $sql = preg_replace_callback(static::PLACEHOLDER_REGEX, [$this, 'parseQuery'], $sql);

        return parent::prepare($sql, $driverOptions);
    }

    /**
     * @return static
     * @throws Exception
     */
    public function beginTransaction()
    {
        parent::beginTransaction();

        return $this;
    }

    /**
     * @return static
     */
    public function rollback()
    {
        parent::rollBack();

        return $this;
    }

    /**
     * @return static
     */
    public function commit()
    {
        parent::commit();

        return $this;
    }

    /**
     * @param string $column ...
     *
     * @return SelectQuery
     */
    public function select()
    {
        return new SelectQuery($this->grammar, func_get_args());
    }

    protected function parseQuery($matches)
    {
        $name = $matches[2];
        $this->types[$name] = static::$typeMap[$matches[1]];
        $this->placeholders[] = $name;

        return '?';
    }

    /**
     * @see prepare
     */
    public function __invoke($sql, array $driverOptions = [])
    {
        return $this->prepare($sql, $driverOptions);
    }
}

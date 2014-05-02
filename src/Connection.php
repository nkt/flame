<?php

namespace Flame;

use Flame\Grammar\Grammar;
use Flame\QueryBuilder\InsertQuery;
use Flame\QueryBuilder\SelectQuery;
use Flame\QueryBuilder\UpdateQuery;

/**
 * Connection
 * @author Gusakov Nikita <dev@nkt.me>
 */
class Connection extends \PDO
{
    const PLACEHOLDER_REGEX = '~([sbnilfdt]{0,1}):(\w+)~';
    const PARAM_DATE_TIME = -1;
    const PARAM_TIME = -2;
    protected static $typeMap = [
        ''  => self::PARAM_STR, // string by default
        's' => self::PARAM_STR,
        'i' => self::PARAM_INT,
        'f' => self::PARAM_STR,
        'b' => self::PARAM_BOOL,
        'n' => self::PARAM_NULL,
        'l' => self::PARAM_LOB,
        'd' => self::PARAM_DATE_TIME,
        't' => self::PARAM_TIME
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
            \PDO::ATTR_STATEMENT_CLASS => ['Flame\\Query', [&$this->grammar, &$this->placeholders, &$this->types]]
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
     * @param string $column,...
     *
     * @return SelectQuery
     */
    public function select($column = null)
    {
        return new SelectQuery($this->grammar, $column === null ? [] : func_get_args());
    }

    /**
     * @param string $table
     * @param array  $columns
     *
     * @return InsertQuery
     */
    public function insert($table, array $columns = [])
    {
        return new InsertQuery($this->grammar, $table, $columns);
    }

    /**
     * @param string $table
     * @param array  $columns
     *
     * @return UpdateQuery
     */
    public function update($table, array $columns = [])
    {
        return new UpdateQuery($this->grammar, $table, $columns);
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

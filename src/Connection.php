<?php

namespace Flame;

/**
 * Flame
 * @author Gusakov Nikita <dev@nkt.me>
 */
class Connection extends \PDO
{
    const PLACEHOLDER_REGEX = '~([sbnilf]{0,1}):(\w+)~';
    protected static $typeMap = [
        's' => \PDO::PARAM_STR,
        ''  => \PDO::PARAM_STR, // string by default
        'b' => \PDO::PARAM_BOOL,
        'n' => \PDO::PARAM_NULL,
        'i' => \PDO::PARAM_INT,
        'l' => \PDO::PARAM_LOB,
        'f' => \PDO::PARAM_STR
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
     * @param string $dsn
     * @param string $username
     * @param string $password
     * @param array  $attributes
     */
    public function __construct($dsn, $username = null, $password = null, array $attributes = [])
    {
        parent::__construct($dsn, $username, $password, array_replace($attributes, [
            \PDO::ATTR_ERRMODE         => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_STATEMENT_CLASS => ['Flame\\Statement', [&$this->placeholders, &$this->types]]
        ]));
    }

    /**
     * @param string $sql
     * @param array  $driverOptions
     *
     * @return Statement
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
     * @throws Exception
     */
    public function rollback()
    {
        parent::rollBack();

        return $this;
    }

    public function commit()
    {
        parent::commit();

        return $this;
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

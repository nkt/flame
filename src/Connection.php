<?php

namespace Flame;

/**
 * Flame
 * @author Gusakov Nikita <dev@nkt.me>
 */
class Connection extends \PDO
{
    protected static $typeMap = [
        'b' => \PDO::PARAM_BOOL,
        'n' => \PDO::PARAM_NULL,
        'i' => \PDO::PARAM_INT,
        's' => \PDO::PARAM_STR,
        'l' => \PDO::PARAM_LOB,
        'f' => \PDO::PARAM_STR
    ];

    /**
     * @param string $dsn
     * @param string $username
     * @param string $password
     * @param array  $attributes
     */
    public function __construct($dsn, $username = null, $password = null, array $attributes = [])
    {
        parent::__construct($dsn, $username, $password, $attributes);
        $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(\PDO::ATTR_STATEMENT_CLASS, ['Flame\\Statement']);
    }

    /**
     * @param string $sql
     * @param array  $driverOptions
     *
     * @return Statement
     */
    public function prepare($sql, array $driverOptions = [])
    {
        $stmt = parent::prepare($sql, $driverOptions);

        return $stmt;
    }

    /**
     * @return static
     * @throws Exception
     */
    public function beginTransaction()
    {
        if (parent::beginTransaction() === false) {
            throw new Exception();
        }

        return $this;
    }

    /**
     * @return static
     * @throws Exception
     */
    public function rollback()
    {
        if (parent::rollback() === false) {
            throw new Exception();
        }

        return $this;
    }

    public function commit()
    {
        if (parent::commit() === false) {
            throw new Exception();
        }

        return $this;
    }

    /**
     * @see prepare
     */
    public function __invoke($sql, array $driverOptions = [])
    {
        return $this->prepare($sql, $driverOptions);
    }
}

<?php

namespace Flame\Test;

use Flame\Connection;

class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Connection
     */
    private $connection;

    protected function setUp()
    {
        $this->connection = new Connection('sqlite::memory:');
        $this->connection->query('CREATE TABLE users (id INT PRIMARY KEY, username CHAR(50), sex BOOL)');
    }

    public function testFluent()
    {
        $this->assertSame($this->connection, $this->connection->beginTransaction());
        $this->connection->query("INSERT INTO users(username) VALUES('John Doe')");
        $this->assertSame($this->connection, $this->connection->rollback());
        $this->assertEmpty($this->connection->query('SELECT * FROM users')->fetchAll());
    }

    public function testParser()
    {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE id IN(:default, s:string, i:int, f:float, b:bool, n:null, l:lob)');

        $this->assertSame('SELECT * FROM users WHERE id IN(?, ?, ?, ?, ?, ?, ?)', $stmt->queryString);
        $this->assertSame(
            array('default', 'string', 'int', 'float', 'bool', 'null', 'lob'),
            $this->getObjectAttribute($stmt, 'placeholders')
        );
        $this->assertSame(array(
            'default' => \PDO::PARAM_STR,
            'string'  => \PDO::PARAM_STR,
            'int'     => \PDO::PARAM_INT,
            'float'   => \PDO::PARAM_STR,
            'bool'    => \PDO::PARAM_BOOL,
            'null'    => \PDO::PARAM_NULL,
            'lob'     => \PDO::PARAM_LOB
        ), $this->getObjectAttribute($stmt, 'types'));
    }
}

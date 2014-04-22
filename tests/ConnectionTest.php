<?php

namespace Flame\Test;

use Flame\Connection;
use Flame\Grammar\MysqlGrammar;

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

    public function testAnotherGrammar()
    {
        $grammar = new MysqlGrammar();
        $conn = new Connection('sqlite::memory:', '', '', [], $grammar);

        $this->assertSame($grammar, $this->getObjectAttribute($conn, 'grammar'));
    }

    public function testFluent()
    {
        $this->assertSame($this->connection, $this->connection->beginTransaction());
        $this->connection->query("INSERT INTO users(username) VALUES('John Doe')");
        $this->assertSame($this->connection, $this->connection->rollback());

        $this->connection->beginTransaction();

        $this->assertSame($this->connection, $this->connection->commit());
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

    public function testSelect()
    {
        $this->assertInstanceOf('Flame\\Query\\SelectQuery', $this->connection->select());
        $select = $this->connection->select('id', 'name', 'count');
        $this->assertSame(['id', 'name', 'count'], $this->getObjectAttribute($select, 'columns'));
    }

    public function testInvoke()
    {
        $connection = $this->connection;
        $this->assertEquals(
            $connection->prepare('SELECT * FROM users'),
            $connection('SELECT * FROM users')
        );
    }
}

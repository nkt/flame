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

    public function testSetDefaultFetchMode()
    {
        $this->assertSame(\PDO::FETCH_ASSOC, $this->connection->getAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE));

        $mode = \PDO::FETCH_OBJ;

        $this->assertSame($this->connection, $this->connection->setDefaultFetchMode($mode));
        $this->assertSame($mode, $this->connection->getAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE));
    }

    public function testAnotherGrammar()
    {
        $grammar = new MysqlGrammar();
        $conn = new Connection('sqlite::memory:', '', '', [], $grammar);

        $this->assertAttributeSame($grammar, 'grammar', $conn);
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
        $stmt = $this->connection->prepare(
            'SELECT * FROM users WHERE id IN(:default, s:string, i:int, f:float, b:bool, n:null, l:lob, d:date, t:time)'
        );

        $this->assertSame('SELECT * FROM users WHERE id IN(?, ?, ?, ?, ?, ?, ?, ?, ?)', $stmt->queryString);
        $this->assertSame(
            array('default', 'string', 'int', 'float', 'bool', 'null', 'lob', 'date', 'time'),
            $this->getObjectAttribute($stmt, 'placeholders')
        );
        $this->assertAttributeSame(array(
            'default' => \PDO::PARAM_STR,
            'string'  => \PDO::PARAM_STR,
            'int'     => \PDO::PARAM_INT,
            'float'   => \PDO::PARAM_STR,
            'bool'    => \PDO::PARAM_BOOL,
            'null'    => \PDO::PARAM_NULL,
            'lob'     => \PDO::PARAM_LOB,
            'date'    => Connection::PARAM_DATE_TIME,
            'time'    => Connection::PARAM_TIME
        ), 'types', $stmt);
    }

    public function testSelect()
    {
        $select = $this->connection->select('id', 'name', 'count');

        $this->assertInstanceOf('Flame\\QueryBuilder\\SelectQuery', $select);
        $this->assertAttributeSame(['id', 'name', 'count'], 'columns', $select);
    }

    public function testInsert()
    {
        $insert = $this->connection->insert('test', ['foo' => 'bar']);

        $this->assertInstanceOf('Flame\\QueryBuilder\\InsertQuery', $insert);
        $this->assertAttributeSame('test', 'table', $insert);
        $this->assertAttributeSame(['"foo"' => 'bar'], 'columns', $insert);
    }

    public function testUpdate()
    {
        $update = $this->connection->update('test', ['foo' => 'bar']);

        $this->assertInstanceOf('Flame\\QueryBuilder\\UpdateQuery', $update);
        $this->assertAttributeSame('test', 'table', $update);
        $this->assertAttributeSame(['"foo"' => 'bar'], 'columns', $update);
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

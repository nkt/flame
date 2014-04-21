<?php

namespace Flame\Test;

use Flame\Connection;
use Flame\Statement;

class StatementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var Statement
     */
    private $insertQuery;

    /**
     * @var Statement
     */
    private $selectQuery;

    protected function setUp()
    {
        $this->connection = new Connection('sqlite::memory:');
        $this->connection->query('CREATE TABLE users (id INT PRIMARY KEY, username CHAR(50), age INT)');
        $this->insertQuery = $this->connection->prepare('INSERT INTO users (username, age) VALUES(s:username, i:age)');
        $this->selectQuery = $this->connection->prepare('SELECT * FROM users');

        $this->insertQuery->execute(['username' => 'nkt', 'age' => 20]);
    }

    public function testValueConverters()
    {
        $this->insertQuery->execute(['username' => null, 'age' => '20foobar']);
        $insertedData = $this->connection->query('SELECT username, age FROM users ORDER BY id DESC')->fetch(\PDO::FETCH_ASSOC);

        $this->assertEquals(['username' => null, 'age' => '20'], $insertedData);
    }

    public function testFetchCallback()
    {
        $i = 0;
        $results = $this->selectQuery->execute()->fetchCallback(function ($data) use (&$i) {
            $i++;

            return $data['id'];
        });
        $this->assertCount($i, $results);
    }

    public function testCloseCursor()
    {
        iterator_to_array($this->selectQuery->execute());
        $this->assertEquals($this->selectQuery, $this->selectQuery->closeCursor());
    }

    public function testInvoke()
    {
        $stmt = $this->selectQuery;

        $this->assertSame($this->selectQuery, $stmt());
    }

    public function testToString()
    {
        $this->assertEquals($this->insertQuery->queryString, (string)$this->insertQuery);
    }
}

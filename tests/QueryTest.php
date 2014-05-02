<?php

namespace Flame\Test;

use Flame\Connection;
use Flame\Grammar\Grammar;
use Flame\Query;

class StatementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var Query
     */
    private $insertQuery;

    /**
     * @var Query
     */
    private $selectQuery;

    protected function setUp()
    {
        $this->connection = new Connection('sqlite::memory:');
        $this->connection->query(
            'CREATE TABLE users (id INT PRIMARY KEY, username CHAR(50), age INT, registered DATE, spend_time TIME)'
        );
        $this->insertQuery = $this->connection->prepare(
            'INSERT INTO users (username, age, registered, spend_time) VALUES(s:username, i:age, d:date, t:time)'
        );
        $this->selectQuery = $this->connection->prepare('SELECT * FROM users');
    }

    public function testValueConverters()
    {
        $this->insertQuery->execute(['username' => null, 'age' => '20foobar']);
        $user = $this->connection->query('SELECT username, age FROM users ORDER BY id DESC')->fetch(\PDO::FETCH_ASSOC);

        $this->assertEquals(['username' => null, 'age' => '20'], $user);

        $date = new \DateTime();
        $this->insertQuery->execute(['username' => 'foo', 'date' => $date, 'time' => $date]);
        $user = $this->connection->query('SELECT registered, spend_time FROM users WHERE username = "foo"')->fetch(\PDO::FETCH_ASSOC);

        $this->assertEquals([
            'registered' => $date->format(Grammar::DATE_TIME_FORMAT),
            'spend_time' => $date->format(Grammar::TIME_FORMAT)
        ], $user);
    }

    public function testFetchCallback()
    {
        $this->insertQuery->execute(['username' => 'nkt', 'age' => 20]);
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

Flame
=====

[![Build Status](https://travis-ci.org/nkt/flame.svg?branch=master)](https://travis-ci.org/nkt/flame)
[![Coverage Status](https://coveralls.io/repos/nkt/flame/badge.png?branch=master)](https://coveralls.io/r/nkt/flame?branch=master)

The PDO wrapper with comfortable API for placeholders

Idea
----

Write placeholders types directly in the query

For example:

```sql
SELECT * FROM goods g WHERE g.price BETWEEN f:minPrice AND f:maxPrice;
SELECT * FROM users u WHERE u.username = s:username; -- :username also bind as string
SELECT * FROM orders o WHERE o.id = i:id;
SELECT * FROM users u WHERE u.registered >= d:date;
```

Difference between native PDO placeholders
------------------------------------------

Unlike PDO you can re-use the same placeholder as long as necessary.
Also you have to specifying the type of the placeholder just once.

```php
$users = $flame->prepare(
    'SELECT * FROM users WHERE age >= i:age OR (registered < d:registered AND age = :age)'
)->execute(['age' => $age]);
```

You don't need cast every integer values, Flame do it for you.

```php
$stmt = $flame->prepare('SELECT * FROM users WHERE id = i:id)');
$users = $stmt->execute(['id' => $_POST['id']]);
```

Every query execution Flame tests value is `null` and if it is,
change placeholder type to `PDO::PARAM_NULL`.

```php
$stmt = $flame->prepare('INSERT INTO users VALUES(s:username, d:last_login))');
$users = $stmt->execute(['username' => 'John Doe', 'last_login' => null]);
```

Flame add new types: date and time. You can bind this data as `DateTime`, `string` or `int`.

Placeholder types
-----------------

 - **s**: string
 - **i**: integer
 - **f**: float
 - **b**: boolean
 - **n**: null
 - **l**: blob
 - **d**: datetime
 - **t**: time


Query builder
=============

Flame also provide powerful query builder. Connection provide base wrappers:

 - `Connection::select(string $column...)`
 - `Connection::update(string $table, array $columns)`
 - `Connection::insert(string $table, array $columns)`

Every sql statement provides by method with same name in `camelCase`.

Examples:

```php
$posts = $db->prepare(
    $db->select('p.id', 'p.title', 'p.content')
       ->from('posts p')
       ->join('post_tags pt', 'p.id', 'pt.post_id')
       ->join('tags t', 't.id', 'pt.tag_id')
       ->where(function ($e) {
           $e->equal('t.name', ':tag');
       })
)->execute(['tag' => $tag]);

$db->prepare($db->insert('users', [
        'username'   => ':name',
        'password'   => ':pass',
        'registered' => 'd:now'
    ]))->execute([
        'name' => $name,
        'pass' => $pass,
        'now'  => new \DateTime()
   ]);
```

Usage
-----

Flame required php 5.4+ and PDO extension.

`composer require nkt/flame:1.0-dev`

```php
<?php

use Flame\Connection;
use Flame\Grammar\MysqlGrammar;

$db = new Connection('mysql:dbname=hello_world', 'user', 'password', [], new MysqlGrammar());
$db->prepare(...);
```

License
-------

[MIT](LICENSE)

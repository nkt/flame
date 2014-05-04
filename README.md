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
$stmt = $flame->prepare('SELECT * FROM users WHERE age >= i:age OR (registered < d:registered AND age = :age)');
$users = $stmt->execute(['age' => $age]);
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

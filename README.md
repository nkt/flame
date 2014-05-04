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

Placeholder types:

 - **s**: string
 - **i**: integer
 - **f**: float
 - **b**: boolean
 - **n**: null
 - **l**: blob
 - **d**: datetime
 - **t**: time

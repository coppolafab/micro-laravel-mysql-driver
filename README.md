# What is this?

A minimal extension of Laravel default behaviour to minimize interactions after connection towards a MySql Server.

## What are the differences?

* Set charset into PDO DSN.
* Do not execute the ```use ${dbname}``` statement, already declared into PDO DSN.
* Do not override server sql_mode by default. Any custom mode set with database.connections.mysql.modes or database.connections.mysql.strict is preserved.
* Do not execute ```set names ...``` unless a collation is provided.
* Avoid prepared statements creation.
* When using Cluster with Replicas, and connection stickiness is enabled, close immediately the read-only connection after a write. 

## How to use

* ```composer require coppolafab/micro-laravel-mysql-driver```
* Add these to your mysql database config: ```'microOverrideDriver' => env('DB_MICROMYSQL_OVERRIDE_DRIVER', false), 'microCloseReadConnectionAfterWrite' => env('DB_MICROMYSQL_CLOSE_READ_CONNECTION_AFTER_WRITE', false),```

Enable those via .env file.

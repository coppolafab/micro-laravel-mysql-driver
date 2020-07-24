# What is this?
A minimal extension of Laravel default behaviour to minimize interactions after connection towards a MySql Server. Compatible with Laravel 5.7+, 6.x and 7.x .

## Why?
* ```set names ...``` statement affects only the server, causing possible differences on operations that involve charsets, especially while enabling prepared statement emulation.<br>Setting connection charset via PDO DSN, instead, affect both client and server.
* Declaring the connection database into PDO DSN, allow to automatically select the db, without execute more statements.<br>Also, some Connections Proxies do not allow execution of ```use ${dbname}``` statement.
* ```set names ...``` statement could be necessary if you need to specify a collation different from the server default for the specified charset. If you can stick with the default (ie. for utf8mb4, is utf8mb4_general_ci on MySQL 5.7 and utf8mb4_0900_ai_ci on MySQL 8.0), you should unset ```database.connections.mysql.collation```.
* Such configuration statements, can cause connection pinning on Proxies.
* When using Cluster with read-only Replicas, any script execution that also writes to the DB, handles 2 connections. If using stickiness, the read-only handle is unused and unnecessary occupying a MySQL thread.

#### MySQL general log output with default driver and config:
```
2020-07-24T12:55:15.580117Z	    3 Connect	db_user@127.0.0.1 on db_schema using TCP/IP
2020-07-24T12:55:15.581060Z	    3 Query	use `db_schema`
2020-07-24T12:55:15.582896Z	    3 Prepare	set names 'utf8mb4'
2020-07-24T12:55:15.583003Z	    3 Execute	set names 'utf8mb4'
2020-07-24T12:55:15.583159Z	    3 Close stmt	
2020-07-24T12:55:15.583256Z	    3 Prepare	set session sql_mode='NO_ENGINE_SUBSTITUTION'
2020-07-24T12:55:15.583331Z	    3 Execute	set session sql_mode='NO_ENGINE_SUBSTITUTION'
2020-07-24T12:55:15.583448Z	    3 Close stmt	
2020-07-24T12:55:15.583531Z	    3 Query	select true
```

#### MySQL general log output with micro driver:
```
2020-07-24T12:51:18.507740Z	    2 Connect	db_user@127.0.0.1 on db_schema using TCP/IP
2020-07-24T12:51:18.509693Z	    2 Query	select true
```
This result can be obtained with all ```database.connections.mysql.collation```, ```database.connections.mysql.strict``` and ```database.connections.mysql.timezone``` set to ```NULL```.

## What are the differences?
* Set charset into PDO DSN.
* Do not execute the ```use ${dbname}``` statement, read from PDO DSN.
* Do not execute ```set names ...``` unless you provide an explicit ```database.connections.mysql.collation```.
* When using Cluster with read-only Replicas, and ```database.connections.mysql.sticky``` is true, close immediately the read-only connection after the switch to the read-write instance. 

## How to use
* Run ```composer require coppolafab/micro-laravel-mysql-driver```
* Add database config options to the mysql section:
    ```
    'microOverrideDriver' => env('DB_MICROMYSQL_OVERRIDE_DRIVER', false),
    'microCloseReadConnectionAfterWrite' => env('DB_MICROMYSQL_CLOSE_READ_CONNECTION_AFTER_WRITE', false),
    ```
* Enable new config options via .env file:
    ```
    DB_MICROMYSQL_OVERRIDE_DRIVER=true
    DB_MICROMYSQL_CLOSE_READ_CONNECTION_AFTER_WRITE=true
    ```

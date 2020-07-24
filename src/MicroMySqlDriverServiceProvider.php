<?php
declare(strict_types=1);

namespace coppolafab\MicroMySqlDriver;

use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

class MicroMySqlDriverServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (config('database.connections.mysql.microOverrideDriver')) {
            $this->app->bind('db.connector.mysql', function () {
                return new MicroMySqlConnector();
            });
        }

        if (config('database.connections.mysql.sticky') && config('database.connections.mysql.microCloseReadConnectionAfterWrite')) {
            Connection::resolverFor('mysql', function ($connection, $database, $prefix, $config) {
                return new MicroMySqlConnection($connection, $database, $prefix, $config);
            });
        }
    }
}

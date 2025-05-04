<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial\Connectors;

use Illuminate\Database\Connectors\ConnectionFactory as IlluminateConnectionFactory;
use ShashwatKalpvaig\LaravelMysqlSpatial\MysqlConnection;
use PDO;

class ConnectionFactory extends IlluminateConnectionFactory
{
    /**
     * @param  string  $driver
     * @param  \Closure|PDO  $connection
     * @param  string  $database
     * @param  string  $prefix
     * @return \Illuminate\Database\ConnectionInterface
     */
    protected function createConnection($driver, $connection, $database, $prefix = '', array $config = [])
    {
        if ($this->container->bound($key = "db.connection.{$driver}")) {
            return $this->container->make($key, [$connection, $database, $prefix, $config]);    // @codeCoverageIgnore
        }

        if ($driver === 'mysql') {
            return new MysqlConnection($connection, $database, $prefix, $config);
        }

        return parent::createConnection($driver, $connection, $database, $prefix, $config);
    }
}

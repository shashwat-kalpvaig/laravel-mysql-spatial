<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial\Tests\Unit\Connectors;

use Illuminate\Container\Container;
use ShashwatKalpvaig\LaravelMysqlSpatial\Connectors\ConnectionFactory;
use ShashwatKalpvaig\LaravelMysqlSpatial\MysqlConnection;
use ShashwatKalpvaig\LaravelMysqlSpatial\Tests\Unit\BaseTestCase;
use Mockery;

class ConnectionFactoryTest extends BaseTestCase
{
    public function testMakeCallsCreateConnection()
    {
        $pdo = $this->createMock(\PDO::class);

        $factory = Mockery::mock(ConnectionFactory::class, [new Container()])->makePartial();
        $factory->shouldAllowMockingProtectedMethods();
        $conn = $factory->createConnection('mysql', $pdo, 'database');

        $this->assertInstanceOf(MysqlConnection::class, $conn);
    }

    public function testCreateConnectionDifferentDriver()
    {
        $pdo = $this->createMock(\PDO::class);

        $factory = Mockery::mock(ConnectionFactory::class, [new Container()])->makePartial();
        $factory->shouldAllowMockingProtectedMethods();
        $conn = $factory->createConnection('pgsql', $pdo, 'database');

        $this->assertInstanceOf(\Illuminate\Database\PostgresConnection::class, $conn);
    }
}

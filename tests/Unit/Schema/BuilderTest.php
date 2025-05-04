<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial\Tests\Unit\Schema;

use ShashwatKalpvaig\LaravelMysqlSpatial\MysqlConnection;
use ShashwatKalpvaig\LaravelMysqlSpatial\Schema\Blueprint;
use ShashwatKalpvaig\LaravelMysqlSpatial\Schema\Builder;
use ShashwatKalpvaig\LaravelMysqlSpatial\Tests\Unit\BaseTestCase as UnitBaseTestCase;
use Mockery;

class BuilderTest extends UnitBaseTestCase
{
    public function testReturnsCorrectBlueprint()
    {
        $connection = Mockery::mock(MysqlConnection::class);
        $connection->shouldReceive('getSchemaGrammar')->once()->andReturn(null);

        $mock = Mockery::mock(Builder::class, [$connection]);
        $mock->makePartial()->shouldAllowMockingProtectedMethods();
        $blueprint = $mock->createBlueprint('test', function () {
        });

        $this->assertInstanceOf(Blueprint::class, $blueprint);
    }
}

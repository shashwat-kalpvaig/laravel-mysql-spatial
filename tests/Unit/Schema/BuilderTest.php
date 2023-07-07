<?php

namespace Limenet\LaravelMysqlSpatial\Tests\Unit\Schema;

use Limenet\LaravelMysqlSpatial\MysqlConnection;
use Limenet\LaravelMysqlSpatial\Schema\Blueprint;
use Limenet\LaravelMysqlSpatial\Schema\Builder;
use Limenet\LaravelMysqlSpatial\Tests\Unit\BaseTestCase as UnitBaseTestCase;
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

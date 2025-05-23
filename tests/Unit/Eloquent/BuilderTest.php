<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial\Tests\Unit\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
use ShashwatKalpvaig\LaravelMysqlSpatial\Eloquent\Builder;
use ShashwatKalpvaig\LaravelMysqlSpatial\Eloquent\SpatialExpression;
use ShashwatKalpvaig\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use ShashwatKalpvaig\LaravelMysqlSpatial\MysqlConnection;
use ShashwatKalpvaig\LaravelMysqlSpatial\Tests\Unit\BaseTestCase as UnitBaseTestCase;
use ShashwatKalpvaig\LaravelMysqlSpatial\Types\LineString;
use ShashwatKalpvaig\LaravelMysqlSpatial\Types\Point;
use ShashwatKalpvaig\LaravelMysqlSpatial\Types\Polygon;
use Mockery;

class BuilderTest extends UnitBaseTestCase
{
    protected $builder;

    protected $queryBuilder;

    protected function setUp(): void
    {
        $connection = Mockery::mock(MysqlConnection::class)->makePartial();
        $grammar = Mockery::mock(MySqlGrammar::class)->makePartial();
        $this->queryBuilder = Mockery::mock(QueryBuilder::class, [$connection, $grammar]);

        $this->queryBuilder
            ->shouldReceive('from')
            ->once()
            ->andReturn($this->queryBuilder);

        $this->builder = new Builder($this->queryBuilder);
        $this->builder->setModel(new class extends Model
        {
            use SpatialTrait;

            public $timestamps = false;

            protected $spatialFields = ['point', 'linestring', 'polygon'];
        });
    }

    public function testUpdatePoint()
    {
        $point = new Point(1, 2);
        $this->queryBuilder
            ->shouldReceive('update')
            ->with(['point' => new SpatialExpression($point)])
            ->once()
            ->andReturn(1);

        $result = $this->builder->update(['point' => $point]);

        $this->assertSame(1, $result);
    }

    public function testUpdateLinestring()
    {
        $linestring = new LineString([new Point(0, 0), new Point(1, 1), new Point(2, 2)]);

        $this->queryBuilder
            ->shouldReceive('update')
            ->with(['linestring' => new SpatialExpression($linestring)])
            ->once()
            ->andReturn(1);

        $result = $this->builder->update(['linestring' => $linestring]);

        $this->assertSame(1, $result);
    }

    public function testUpdatePolygon()
    {
        $linestrings[] = new LineString([new Point(0, 0), new Point(0, 1)]);
        $linestrings[] = new LineString([new Point(0, 1), new Point(1, 1)]);
        $linestrings[] = new LineString([new Point(1, 1), new Point(0, 0)]);
        $polygon = new Polygon($linestrings);

        $this->queryBuilder
            ->shouldReceive('update')
            ->with(['polygon' => new SpatialExpression($polygon)])
            ->once()
            ->andReturn(1);

        $result = $this->builder->update(['polygon' => $polygon]);

        $this->assertSame(1, $result);
    }

    public function testUpdatePointWithSrid()
    {
        $point = new Point(1, 2, 4326);
        $this->queryBuilder
            ->shouldReceive('update')
            ->with(['point' => new SpatialExpression($point)])
            ->once()
            ->andReturn(1);

        $result = $this->builder->update(['point' => $point]);

        $this->assertSame(1, $result);
    }

    public function testUpdateLinestringWithSrid()
    {
        $linestring = new LineString([new Point(0, 0), new Point(1, 1), new Point(2, 2)], 4326);

        $this->queryBuilder
            ->shouldReceive('update')
            ->with(['linestring' => new SpatialExpression($linestring)])
            ->once()
            ->andReturn(1);

        $result = $this->builder->update(['linestring' => $linestring]);

        $this->assertSame(1, $result);
    }

    public function testUpdatePolygonWithSrid()
    {
        $linestrings[] = new LineString([new Point(0, 0), new Point(0, 1)]);
        $linestrings[] = new LineString([new Point(0, 1), new Point(1, 1)]);
        $linestrings[] = new LineString([new Point(1, 1), new Point(0, 0)]);
        $polygon = new Polygon($linestrings, 4326);

        $this->queryBuilder
            ->shouldReceive('update')
            ->with(['polygon' => new SpatialExpression($polygon)])
            ->once()
            ->andReturn(1);

        $result = $this->builder->update(['polygon' => $polygon]);

        $this->assertSame(1, $result);
    }
}

<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial\Eloquent;

use Illuminate\Database\Grammar;
use Illuminate\Database\Query\Expression;
use ShashwatKalpvaig\LaravelMysqlSpatial\Types\Geometry;
use ShashwatKalpvaig\LaravelMysqlSpatial\Types\GeometryInterface;

class SpatialExpression extends Expression
{
    /**
     * @param  Geometry|GeometryInterface  $value
     * @return void
     */
    public function __construct(protected $value)
    {
    }

    public function getValue(Grammar $grammar)
    {
        return "ST_GeomFromText(?, ?, 'axis-order=long-lat')";
    }

    public function getSpatialValue(): string
    {
        return $this->value->toWkt();
    }

    public function getSrid(): int
    {
        return $this->value->getSrid();
    }

    public function toWkt(): string
    {
        return $this->value->toWkt();
    }
}

<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use ShashwatKalpvaig\LaravelMysqlSpatial\Types\GeometryInterface;

class Builder extends EloquentBuilder
{
    public function update(array $values)
    {
        foreach ($values as $key => &$value) {
            if ($value instanceof GeometryInterface) {
                $value = $this->asWKT($value);
            }
        }

        return parent::update($values);
    }

    protected function asWKT(GeometryInterface $geometry): SpatialExpression
    {
        return new SpatialExpression($geometry);
    }
}

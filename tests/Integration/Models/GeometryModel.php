<?php

namespace Limenet\LaravelMysqlSpatial\Tests\Integration\Models;

use Illuminate\Database\Eloquent\Model;
use Limenet\LaravelMysqlSpatial\Eloquent\SpatialTrait;

/**
 * @property int                                          id
 * @property \Limenet\LaravelMysqlSpatial\Types\Point      location
 * @property \Limenet\LaravelMysqlSpatial\Types\LineString line
 * @property \Limenet\LaravelMysqlSpatial\Types\LineString shape
 */
class GeometryModel extends Model
{
    use SpatialTrait;

    protected $table = 'geometry';

    protected $spatialFields = ['location', 'line', 'multi_geometries'];
}

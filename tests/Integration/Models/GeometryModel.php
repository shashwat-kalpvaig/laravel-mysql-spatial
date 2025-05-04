<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial\Tests\Integration\Models;

use Illuminate\Database\Eloquent\Model;
use ShashwatKalpvaig\LaravelMysqlSpatial\Eloquent\SpatialTrait;

/**
 * @property int                                          id
 * @property \ShashwatKalpvaig\LaravelMysqlSpatial\Types\Point      location
 * @property \ShashwatKalpvaig\LaravelMysqlSpatial\Types\LineString line
 * @property \ShashwatKalpvaig\LaravelMysqlSpatial\Types\LineString shape
 */
class GeometryModel extends Model
{
    use SpatialTrait;

    protected $table = 'geometry';

    protected $spatialFields = ['location', 'line', 'multi_geometries'];
}

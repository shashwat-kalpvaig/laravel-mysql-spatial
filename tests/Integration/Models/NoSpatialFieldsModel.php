<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial\Tests\Integration\Models;

use Illuminate\Database\Eloquent\Model;
use ShashwatKalpvaig\LaravelMysqlSpatial\Eloquent\SpatialTrait;

/**
 * @property \ShashwatKalpvaig\LaravelMysqlSpatial\Types\Geometry geometry
 */
class NoSpatialFieldsModel extends Model
{
    use SpatialTrait;

    protected $table = 'no_spatial_fields';

    public $timestamps = false;
}

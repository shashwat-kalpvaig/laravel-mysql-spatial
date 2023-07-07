<?php

namespace Limenet\LaravelMysqlSpatial\Tests\Integration\Models;

use Illuminate\Database\Eloquent\Model;
use Limenet\LaravelMysqlSpatial\Eloquent\SpatialTrait;

/**
 * @property \Limenet\LaravelMysqlSpatial\Types\Geometry geometry
 */
class NoSpatialFieldsModel extends Model
{
    use SpatialTrait;

    protected $table = 'no_spatial_fields';

    public $timestamps = false;
}

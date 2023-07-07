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
class WithSridModel extends Model
{
    use SpatialTrait;

    protected $table = 'with_srid';

    protected $spatialFields = ['location', 'line'];

    public $timestamps = false;
}

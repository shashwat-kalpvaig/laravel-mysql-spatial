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
class WithSridModel extends Model
{
    use SpatialTrait;

    protected $table = 'with_srid';

    protected $spatialFields = ['location', 'line'];

    public $timestamps = false;
}

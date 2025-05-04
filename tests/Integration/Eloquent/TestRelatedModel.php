<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial\Tests\Integration\Eloquent;

class TestRelatedModel extends TestModel
{
    public function testmodel()
    {
        return $this->belongsTo(TestModel::class);
    }

    public function testmodels()
    {
        return $this->belongsToMany(TestModel::class);
    }
}

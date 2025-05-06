<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial\Schema;

use Closure;
use Illuminate\Database\Schema\MySqlBuilder;
use ShashwatKalpvaig\LaravelMysqlSpatial\Schema\Blueprint; 

class Builder extends MySqlBuilder
{
    /**
     * Create a new command set with a Closure.
     *
     * @param  string  $table
     * @param  Closure  $callback
     * @return Blueprint
     */
    public function createBlueprint($table, Closure $callback = null)
    {
        return new Blueprint($this->connection, $table, $callback);
    }
}

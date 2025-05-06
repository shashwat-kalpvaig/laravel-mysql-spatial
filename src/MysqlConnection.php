<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial;

use Doctrine\DBAL\Types\Type as DoctrineType;
use Illuminate\Database\MySqlConnection as IlluminateMySqlConnection;
use Illuminate\Database\Schema\Grammars\Grammar;
use ShashwatKalpvaig\LaravelMysqlSpatial\Schema\Builder;
use ShashwatKalpvaig\LaravelMysqlSpatial\Schema\Grammars\MySqlGrammar;

class MysqlConnection extends IlluminateMySqlConnection
{
    public function __construct($pdo, $database = '', $tablePrefix = '', array $config = [])
    {
        parent::__construct($pdo, $database, $tablePrefix, $config);

        if (class_exists(DoctrineType::class)) {
            // Prevent geometry type fields from throwing a 'type not found' error when changing them
            $geometries = [
                'geometry',
                'point',
                'linestring',
                'polygon',
                'multipoint',
                'multilinestring',
                'multipolygon',
                'geometrycollection',
                'geomcollection',
            ];
            $dbPlatform = $this->getDoctrineConnection()->getDatabasePlatform();
            foreach ($geometries as $type) {
                $dbPlatform->registerDoctrineTypeMapping($type, 'string');
            }
        }
    }

    /**
     * Get the default schema grammar instance.
     */
    protected function getDefaultSchemaGrammar(): Grammar
    {
        return new MySqlGrammar($this); 
    }


    /**
     * Get a schema builder instance for the connection.
     */
    public function getSchemaBuilder(): \Illuminate\Database\Schema\MySqlBuilder
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new Builder($this);
    }
}

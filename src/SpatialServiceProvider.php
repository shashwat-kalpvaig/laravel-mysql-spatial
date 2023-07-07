<?php

namespace Limenet\LaravelMysqlSpatial;

use Doctrine\DBAL\Types\Type as DoctrineType;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\DatabaseServiceProvider;
use Limenet\LaravelMysqlSpatial\Connectors\ConnectionFactory;
use Limenet\LaravelMysqlSpatial\Doctrine\Geometry;
use Limenet\LaravelMysqlSpatial\Doctrine\GeometryCollection;
use Limenet\LaravelMysqlSpatial\Doctrine\LineString;
use Limenet\LaravelMysqlSpatial\Doctrine\MultiLineString;
use Limenet\LaravelMysqlSpatial\Doctrine\MultiPoint;
use Limenet\LaravelMysqlSpatial\Doctrine\MultiPolygon;
use Limenet\LaravelMysqlSpatial\Doctrine\Point;
use Limenet\LaravelMysqlSpatial\Doctrine\Polygon;

/**
 * Class DatabaseServiceProvider.
 */
class SpatialServiceProvider extends DatabaseServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        // The connection factory is used to create the actual connection instances on
        // the database. We will inject the factory into the manager so that it may
        // make the connections while they are actually needed and not of before.
        $this->app->singleton('db.factory', fn ($app) => new ConnectionFactory($app));

        // The database manager is used to resolve various connections, since multiple
        // connections might be managed. It also implements the connection resolver
        // interface which may be used by other components requiring connections.
        $this->app->singleton('db', fn ($app) => new DatabaseManager($app, $app['db.factory']));

        if (class_exists(DoctrineType::class)) {
            // Prevent geometry type fields from throwing a 'type not found' error when changing them
            $geometries = [
                'geometry' => Geometry::class,
                'point' => Point::class,
                'linestring' => LineString::class,
                'polygon' => Polygon::class,
                'multipoint' => MultiPoint::class,
                'multilinestring' => MultiLineString::class,
                'multipolygon' => MultiPolygon::class,
                'geometrycollection' => GeometryCollection::class,
            ];
            $typeNames = array_keys(DoctrineType::getTypesMap());
            foreach ($geometries as $type => $class) {
                if (! in_array($type, $typeNames)) {
                    DoctrineType::addType($type, $class);
                }
            }
        }
    }
}

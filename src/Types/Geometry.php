<?php

namespace Limenet\LaravelMysqlSpatial\Types;

use GeoIO\WKB\Parser\Parser;
use GeoJson\Feature\Feature;
use GeoJson\GeoJson;
use Illuminate\Contracts\Support\Jsonable;
use Limenet\LaravelMysqlSpatial\Exceptions\UnknownWKBException;
use Limenet\LaravelMysqlSpatial\Exceptions\UnknownWKTTypeException;

abstract class Geometry implements \JsonSerializable, GeometryInterface, Jsonable
{
    protected static array $wkb_types = [
        1 => Point::class,
        2 => LineString::class,
        3 => Polygon::class,
        4 => MultiPoint::class,
        5 => MultiLineString::class,
        6 => MultiPolygon::class,
        7 => GeometryCollection::class,
    ];

    public function __construct(protected int $srid = 0)
    {
    }

    public function getSrid(): int
    {
        return $this->srid;
    }

    public function setSrid(int $srid): void
    {
        $this->srid = $srid;
    }

    public static function getWKTArgument(string $value): string
    {
        $left = strpos($value, '(');
        $right = strrpos($value, ')');

        return substr($value, $left + 1, $right - $left - 1);
    }

    /** @return class-string<Geometry> */
    public static function getWKTClass(string $value): string
    {
        $left = strpos($value, '(');

        if ($left === false) {
            throw new UnknownWKTTypeException('Could not parse '.$value);
        }

        $type = trim(substr($value, 0, $left));

        return match (strtoupper($type)) {
            'POINT' => Point::class,
            'LINESTRING' => LineString::class,
            'POLYGON' => Polygon::class,
            'MULTIPOINT' => MultiPoint::class,
            'MULTILINESTRING' => MultiLineString::class,
            'MULTIPOLYGON' => MultiPolygon::class,
            'GEOMETRYCOLLECTION' => GeometryCollection::class,
            default => throw new UnknownWKTTypeException('Type was '.$type)
        };
    }

    public static function fromWKB(string $wkb): Geometry
    {
        $srid = substr($wkb, 0, 4);
        $unpacked = unpack('L', $srid);

        if ($unpacked === false) {
            throw new UnknownWKBException($wkb);
        }

        $srid = $unpacked[1];

        $wkb = substr($wkb, 4);
        $parser = new Parser(new Factory());

        /** @var Geometry $parsed */
        $parsed = $parser->parse($wkb);

        if ($srid > 0) {
            $parsed->setSrid($srid);
        }

        return $parsed;
    }

    public static function fromWKT(string $wkt, int $srid = 0): static
    {
        $wktArgument = static::getWKTArgument($wkt);

        return static::fromString($wktArgument, $srid);
    }

    public static function fromJson(string|GeoJson $geoJson): self
    {
        if (is_string($geoJson)) {
            $geoJson = GeoJson::jsonUnserialize(json_decode($geoJson, flags: JSON_THROW_ON_ERROR));
        }

        if ($geoJson->getType() === 'FeatureCollection') {
            return GeometryCollection::fromJson($geoJson);
        }

        /** @var Feature $geoJson */
        if ($geoJson->getType() === 'Feature') {
            $geoJson = $geoJson->getGeometry();
        }

        $type = '\Limenet\LaravelMysqlSpatial\Types\\'.$geoJson?->getType();

        return $type::fromJson($geoJson);
    }

    public function toJson($options = 0)
    {
        return json_encode($this, $options | JSON_THROW_ON_ERROR);
    }
}

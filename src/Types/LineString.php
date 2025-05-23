<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial\Types;

use GeoJson\GeoJson;
use GeoJson\Geometry\LineString as GeoJsonLineString;
use ShashwatKalpvaig\LaravelMysqlSpatial\Exceptions\InvalidGeoJsonException;

class LineString extends PointCollection implements \Stringable
{
    /**
     * The minimum number of items required to create this collection.
     */
    protected int $minimumCollectionItems = 2;

    public function toWKT(): string
    {
        return sprintf('LINESTRING(%s)', $this->toPairList());
    }

    public static function fromWKT(string $wkt, int $srid = 0): static
    {
        $wktArgument = Geometry::getWKTArgument($wkt);

        return static::fromString($wktArgument, $srid);
    }

    public static function fromString(string $wktArgument, int $srid = 0): static
    {
        $pairs = explode(',', trim($wktArgument));
        $points = array_map(fn ($pair) => Point::fromPair($pair), $pairs);

        return new static($points, $srid);
    }

    public function __toString(): string
    {
        return $this->toPairList();
    }

    public static function fromJson(string|GeoJson $geoJson): self
    {
        if (is_string($geoJson)) {
            $geoJson = GeoJson::jsonUnserialize(json_decode($geoJson, null, 512, JSON_THROW_ON_ERROR));
        }

        if (! $geoJson instanceof GeoJsonLineString) {
            throw new InvalidGeoJsonException(GeoJsonLineString::class, $geoJson::class);
        }

        $set = [];
        foreach ($geoJson->getCoordinates() as $coordinate) {
            $set[] = new Point($coordinate[1], $coordinate[0]);
        }

        return new self($set);
    }

    /**
     * Convert to GeoJson LineString that is jsonable to GeoJSON.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $points = [];
        foreach ($this->items as $point) {
            $points[] = $point->jsonSerialize();
        }

        return new GeoJsonLineString($points);
    }
}

<?php

namespace Limenet\LaravelMysqlSpatial\Types;

use GeoJson\GeoJson;
use GeoJson\Geometry\MultiLineString as GeoJsonMultiLineString;
use Limenet\LaravelMysqlSpatial\Exceptions\InvalidGeoJsonException;
use RuntimeException;

/**
 * @implements GeometryInterface<LineString>
 *
 * @extends GeometryCollection<GeometryInterface>
 */
class MultiLineString extends GeometryCollection implements \Stringable, GeometryInterface
{
    /**
     * The minimum number of items required to create this collection.
     */
    protected int $minimumCollectionItems = 1;

    /**
     * The class of the items in the collection.
     */
    protected string $collectionItemType = LineString::class;

    public function getLineStrings(): array
    {
        return $this->items;
    }

    public function toWKT(): string
    {
        return sprintf('MULTILINESTRING(%s)', (string) $this);
    }

    public static function fromString(string $wktArgument, int $srid = 0): static
    {
        $str = preg_split('/\)\s*,\s*\(/', substr(trim($wktArgument), 1, -1));

        if ($str === false) {
            throw new RuntimeException();
        }

        $lineStrings = array_map(fn ($data) => LineString::fromString($data), $str);

        return new static($lineStrings, $srid);
    }

    public function __toString(): string
    {
        return implode(',', array_map(fn (LineString $lineString) => sprintf('(%s)', (string) $lineString), $this->getLineStrings()));
    }

    public function offsetSet($offset, $value): void
    {
        $this->validateItemType($value);

        parent::offsetSet($offset, $value);
    }

    public static function fromJson(string|GeoJson $geoJson): self
    {
        if (is_string($geoJson)) {
            $geoJson = GeoJson::jsonUnserialize(json_decode($geoJson, flags: JSON_THROW_ON_ERROR));
        }

        if (! $geoJson instanceof GeoJsonMultiLineString) {
            throw new InvalidGeoJsonException(GeoJsonMultiLineString::class, $geoJson::class);
        }

        $set = [];
        foreach ($geoJson->getCoordinates() as $coordinates) {
            $points = [];
            foreach ($coordinates as $coordinate) {
                $points[] = new Point($coordinate[1], $coordinate[0]);
            }
            $set[] = new LineString($points);
        }

        return new self($set);
    }

    /**
     * Convert to GeoJson Point that is jsonable to GeoJSON.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $lineStrings = [];

        foreach ($this->items as $lineString) {
            $lineStrings[] = $lineString->jsonSerialize();
        }

        return new GeoJsonMultiLineString($lineStrings);
    }
}

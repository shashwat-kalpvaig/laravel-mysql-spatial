<?php

namespace Limenet\LaravelMysqlSpatial\Types;

use GeoJson\GeoJson;
use GeoJson\Geometry\Point as GeoJsonPoint;
use Limenet\LaravelMysqlSpatial\Exceptions\InvalidGeoJsonException;

/**
 * @implements GeometryInterface<GeoJsonPoint>
 */
class Point extends Geometry implements \Stringable, GeometryInterface
{
    public function __construct(protected float $lat, protected float $lng, ?int $srid = 0)
    {
        parent::__construct((int) $srid);
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function setLat(float $lat): void
    {
        $this->lat = $lat;
    }

    public function getLng(): float
    {
        return $this->lng;
    }

    public function setLng(float $lng): void
    {
        $this->lng = $lng;
    }

    public function toPair(): string
    {
        return $this->getLng().' '.$this->getLat();
    }

    public static function fromPair(string $pair, int $srid = 0): static
    {
        [$lng, $lat] = explode(' ', trim($pair, "\t\n\r \x0B()"));

        return new static((float) $lat, (float) $lng, $srid);
    }

    public function toWKT(): string
    {
        return sprintf('POINT(%s)', (string) $this);
    }

    public static function fromString(string $wktArgument, int $srid = 0): self
    {
        return static::fromPair($wktArgument, $srid);
    }

    public function __toString(): string
    {
        return $this->getLng().' '.$this->getLat();
    }

    /**
     * @param  $geoJson  \GeoJson\Feature\Feature|string
     */
    public static function fromJson(string|GeoJson $geoJson): self
    {
        if (is_string($geoJson)) {
            $geoJson = GeoJson::jsonUnserialize(json_decode($geoJson, flags: JSON_THROW_ON_ERROR));
        }

        if (! $geoJson instanceof GeoJsonPoint) {
            throw new InvalidGeoJsonException(GeoJsonPoint::class, $geoJson::class);
        }

        $coordinates = $geoJson->getCoordinates();

        return new self($coordinates[1], $coordinates[0]);
    }

    /**
     * Convert to GeoJson Point that is jsonable to GeoJSON.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return new GeoJsonPoint([$this->getLng(), $this->getLat()]);
    }
}

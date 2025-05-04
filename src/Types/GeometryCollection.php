<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial\Types;

use ArrayAccess;
use ArrayIterator;
use Countable;
use GeoJson\Feature\FeatureCollection;
use GeoJson\GeoJson;
use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;
use IteratorAggregate;
use ShashwatKalpvaig\LaravelMysqlSpatial\Exceptions\InvalidGeoJsonException;

/**
 * @template GeoType of GeometryInterface
 *
 * @implements GeometryInterface<FeatureCollection>
 */
class GeometryCollection extends Geometry implements IteratorAggregate, ArrayAccess, Arrayable, Countable, GeometryInterface, \Stringable
{
    /**
     * The minimum number of items required to create this collection.
     */
    protected int $minimumCollectionItems = 0;

    /**
     * The class of the items in the collection.
     */
    protected string $collectionItemType = GeometryInterface::class;

    /**
     * The items contained in the spatial collection.
     *
     * @var GeoType[]
     */
    protected array $items = [];

    /**
     * @param  GeoType[]  $geometries
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $geometries, ?int $srid = 0)
    {
        parent::__construct((int) $srid);

        $this->validateItems($geometries);

        $this->items = $geometries;
    }

    /**
     * @return GeoType[]
     */
    public function getGeometries(): array
    {
        return $this->items;
    }

    public function toWKT(): string
    {
        return sprintf('GEOMETRYCOLLECTION(%s)', (string) $this);
    }

    public function __toString(): string
    {
        return implode(',', array_map(fn (GeometryInterface $geometry) => $geometry->toWKT(), $this->items));
    }

    public static function fromString(string $wktArgument, int $srid = 0): static
    {
        if ($wktArgument === '') {
            return new static([]);
        }

        $geometry_strings = preg_split('/,\s*(?=[A-Za-z])/', $wktArgument);
        if ($geometry_strings === false) {
            return new static([]);
        }

        return new static(array_map(fn ($geometry_string) => call_user_func([Geometry::getWKTClass($geometry_string), 'fromWKT'], $geometry_string), $geometry_strings), $srid);
    }

    public function toArray()
    {
        return $this->items;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->items[$offset] : null;
    }

    public function offsetSet($offset, $value): void
    {
        $this->validateItemType($value);

        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public static function fromJson(string|GeoJson $geoJson): self
    {
        if (is_string($geoJson)) {
            $geoJson = GeoJson::jsonUnserialize(json_decode($geoJson, flags: JSON_THROW_ON_ERROR));
        }

        if (! $geoJson instanceof FeatureCollection) {
            throw new InvalidGeoJsonException(FeatureCollection::class, $geoJson::class);
        }

        $set = [];
        foreach ($geoJson->getFeatures() as $feature) {
            $set[] = parent::fromJson($feature);
        }

        return new self($set);
    }

    /**
     * Convert to GeoJson GeometryCollection that is jsonable to GeoJSON.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $geometries = [];
        foreach ($this->items as $geometry) {
            $geometries[] = $geometry->jsonSerialize();
        }

        return new \GeoJson\Geometry\GeometryCollection($geometries);
    }

    /**
     * Checks whether the items are valid to create this collection.
     *
     * @param  GeoType[]  $items
     */
    protected function validateItems(array $items): void
    {
        $this->validateItemCount($items);

        foreach ($items as $item) {
            $this->validateItemType($item);
        }
    }

    /**
     * Checks whether the array has enough items to generate a valid WKT.
     *
     * @param  GeoType[]  $items
     *
     * @see $minimumCollectionItems
     */
    protected function validateItemCount(array $items): void
    {
        if (count($items) < $this->minimumCollectionItems) {
            $entries = $this->minimumCollectionItems === 1 ? 'entry' : 'entries';

            throw new InvalidArgumentException(sprintf(
                '%s must contain at least %d %s',
                static::class,
                $this->minimumCollectionItems,
                $entries
            ));
        }
    }

    /**
     * Checks the type of the items in the array.
     *
     *
     * @see $collectionItemType
     */
    protected function validateItemType(mixed $item): void
    {
        if (! $item instanceof $this->collectionItemType) {
            throw new InvalidArgumentException(sprintf(
                '%s must be a collection of %s',
                static::class,
                $this->collectionItemType
            ));
        }
    }
}

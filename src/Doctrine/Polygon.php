<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class Polygon extends Type
{
    final public const POLYGON = 'polygon';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'polygon';
    }

    public function getName()
    {
        return self::POLYGON;
    }
}

<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class MultiPolygon extends Type
{
    final public const MULTIPOLYGON = 'multipolygon';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'multipolygon';
    }

    public function getName()
    {
        return self::MULTIPOLYGON;
    }
}

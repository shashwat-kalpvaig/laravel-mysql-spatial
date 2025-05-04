<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class Geometry extends Type
{
    final public const GEOMETRY = 'geometry';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'geometry';
    }

    public function getName()
    {
        return self::GEOMETRY;
    }
}

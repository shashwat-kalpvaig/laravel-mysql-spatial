<?php

namespace ShashwatKalpvaig\LaravelMysqlSpatial\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class MultiLineString extends Type
{
    final public const MULTILINESTRING = 'multilinestring';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'multilinestring';
    }

    public function getName()
    {
        return self::MULTILINESTRING;
    }
}

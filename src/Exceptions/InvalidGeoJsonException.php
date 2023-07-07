<?php

namespace Limenet\LaravelMysqlSpatial\Exceptions;

class InvalidGeoJsonException extends \RuntimeException
{
    /**
     * @param  class-string  $expected
     * @param  class-string  $actual
     */
    public function __construct(string $expected, string $actual)
    {
        parent::__construct(
            sprintf('Expected %s, got %s', $expected, $actual)
        );
    }
}

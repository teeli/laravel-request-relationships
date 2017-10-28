<?php

namespace Aciddose\RequestRelationships\Traits;

use Aciddose\RequestRelationships\Scopes\RequestRelationshipsScope;

/**
 * Trait RequestRelationships
 *
 * Automatically load relationships defined in query parameters
 *
 * @package App\Models\Traits
 */
trait RequestRelationships
{
    protected static $input;

    public static function bootRequestRelationships()
    {
        static::addGlobalScope(new RequestRelationshipsScope);
    }

    public function getRequestRelationsParamName()
    {
        if (isset($this->requestRelationsParamName)) {
            return $this->requestRelationsParamName;
        }

        return null;
    }
}

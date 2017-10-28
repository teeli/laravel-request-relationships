<?php
/**
 */

namespace Aciddose\RequestRelationships\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Request;

/**
 * Class RequestRelationshipsScope
 *
 * Automatically load relationships defined in query parameters
 *
 * @package App\Models\Traits
 */
class RequestRelationshipsScope implements Scope
{
    /**
     * @var string The parameter name that should be parse from queries (default: with)
     */
    protected $paramName = 'with';

    public function __construct()
    {
        // set default param name from config if set
        $config = config('requestrelationships');
        if (!empty($config['default_parameter_name'])) {
            $this->paramName = $config['default_parameter_name'];
        }
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model   $model
     * @return Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        // use custom param name if set
        $paramName = $model->getRequestRelationsParamName();
        if (!is_null($paramName)) {
            $this->paramName = $paramName;
        }

        // load defined relations
        $params = $this->parseWith((string)Request::input($this->paramName));
        $with = $this->validateWith($params, $model);
        if (isset($with) && count($with) > 0) {
            return $builder->with($with);
        }

        return $builder;
    }

    /**
     * Parse with params from query into an array
     *
     * @param string $input
     * @return array
     */
    protected function parseWith(string $input = ''): array
    {
        if (strlen($input) > 0) {
            return array_map(function ($param) {
                // cast everything to strings just to be safe
                return (string)$param;
            }, explode(',', $input));
        }

        return [];
    }

    /**
     * Validate that parameters actually exist
     * @param array $params
     * @param Model $model
     * @return array
     */
    protected function validateWith(array $params, Model $model): array
    {
        return array_filter($params, function ($param) use ($model) {
            // skip empty params
            if (empty($param)) {
                return false;
            }

            // If the key already exists in the relationships array, it just means the
            // relationship has already been loaded, so we'll just skip it to avoid
            // loading the same relationship twice.
            if ($model->relationLoaded($param)) {
                return false;
            }

            // If the "attribute" exists as a method on the model, we will just assume
            // it is a relationship.
            if (method_exists($model, $param)) {
                return true;
            }

            // TODO: If there's a way to check nested relations, we should do that and default to false
            return true;
        });
    }
}

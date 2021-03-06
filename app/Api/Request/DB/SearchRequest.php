<?php

namespace App\Api\Request\DB;


use App\Api\Request\PaginatedRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;
use Laravel\Scout\Searchable;

/**
 * An API request to retrieve a list of DB entries based on a search query
 */
abstract class SearchRequest extends PaginatedRequest
{
    use BasicDBRequest;

    /**
     * SearchRequest constructor.
     */
    public function __construct()
    {
        // empty constructor. Should not have the `modelClass` and `resourceClass` parameters.
    }

    /**
     * @inheritDoc
     *
     * @param Collection $parameters
     *
     * @return array
     */
    protected function urlParameters(Collection $parameters)
    {
        return [];
    }

    /**
     * @inheritDoc
     *
     * @param Validator|null $validator
     *
     * @return array
     */
    protected function _rules(
        Collection $parameters,
        Validator $validator = null
    )
    {
        return [
                'query' => 'required|string',
            ] + parent::_rules($parameters, $validator);
    }

    /**
     * @inheritDoc
     *
     * @param Collection $parameters
     *
     * @return array
     */
    protected function _urlParameters(Collection $parameters)
    {
        return array_merge([
            'query',
        ],
            $this->getDBParameters($parameters)->keys()->toArray(),
            parent::_urlParameters($parameters)
        );
    }

    /**
     * Return whether a result can be shown
     *
     * @param Model      $model
     * @param Collection $parameters
     *
     * @return bool
     */
    protected abstract function filterResult($model, Collection $parameters);

    /**
     * @inheritDoc
     *
     * @param Collection $parameters
     * @param            $perPage
     * @param            $pageOrAfter
     *
     * @return LengthAwarePaginator
     */
    protected function paginator(Collection $parameters, $perPage, $pageOrAfter)
    {
        /** @var Model|Searchable $modelClass */
        $modelClass = $this->modelClass();

        $query = $modelClass::search(trim($parameters['query']));

        $results = $query->get();
        $results = $results->filter(function ($model) use ($parameters) {
            return $this->filterResult($model, $parameters);
        });

        return new LengthAwarePaginator($results->forPage($pageOrAfter,
            $perPage),
            $results->count(), $perPage, $pageOrAfter);
    }

}
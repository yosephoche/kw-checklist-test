<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class JsonApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPaginationMacro();
        $this->registerRequestMacro();
    }

    protected function registerPaginationMacro()
    {
        Builder::macro('jsonPaginate', function ($request, $baseUrl = null, int $maxResults = null, int $defaultSize = null) {
            $baseUrl = $baseUrl ?? "http://localhost/v1/checklists";

            $maxResults = $maxResults ?? 30;
            $defaultSize = $defaultSize ?? 10;
            $numberParameter = 'offset';
            $sizeParameter = 'limit';
            $size = (int) $request->input('page.'.$sizeParameter, $defaultSize);
            $size = $size > $maxResults ? $maxResults : $size;
            $paginator = $this
                ->paginate($size, ['*'], 'page.'.$numberParameter)
                ->setPageName('page['.$numberParameter.']')
                ->appends(Arr::except($request->only('filter', 'sort', 'fields'), 'page.'.$numberParameter));
            
            $paginator->setPath($baseUrl);
            
            return $paginator;
        });
    }

    public function registerRequestMacro()
    {
        Request::macro('includes', function ($include = null) {
            $includeParts = $this->query('includes');

            if (! is_array($includeParts)) {
                $includeParts = explode(',', strtolower($this->query($parameter)));
            }

            $includes = collect($includeParts)->filter();

            if (is_null($include)) {
                return $includes;
            }

            return $includes->contains(strtolower($include));
        });

        Request::macro('appends', function ($append = null) {
            $appendParts = $this->query('appends');

            if (! is_array($appendParts)) {
                $appendParts = explode(',', strtolower($this->query($parameter)));
            }

            $appends = collect($appendParts)->filter();

            if (is_null($append)) {
                return $appends;
            }

            return $appends->contains(strtolower($append));
        });

        Request::macro('filters', function ($filter = null) {
            $filterParts = $this->query('filter', []);

            if (is_string($filterParts)) {
                return collect();
            }

            $filters = collect($filterParts);

            $filtersMapper = function ($value) {
                if (is_array($value)) {
                    return collect($value)->map($this->bindTo($this))->all();
                }

                if (Str::contains($value, ',')) {
                    return explode(',', $value);
                }

                if ($value === 'true') {
                    return true;
                }

                if ($value === 'false') {
                    return false;
                }

                return $value;
            };

            $filters = $filters->map($filtersMapper->bindTo($filtersMapper));

            if (is_null($filter)) {
                return $filters;
            }

            return $filters->get(strtolower($filter));
        });

        Request::macro('fields', function (): Collection {
            $fieldsPerTable = collect($this->query('fields'));

            if ($fieldsPerTable->isEmpty()) {
                return collect();
            }

            return $fieldsPerTable->map(function ($fields) {
                return explode(',', $fields);
            });
        });

        Request::macro('sort', function ($default = null) {
            return $this->query('sort', $default);
        });

        Request::macro('sorts', function ($default = null) {
            $sortParts = $this->sort();

            if (! is_array($sortParts)) {
                $sortParts = explode(',', $sortParts);
            }

            $sorts = collect($sortParts)->filter();

            if ($sorts->isNotEmpty()) {
                return $sorts;
            }

            if (! $default instanceof Collection) {
                $default = collect($default);
            }

            return $default->filter();
        });
    }
}
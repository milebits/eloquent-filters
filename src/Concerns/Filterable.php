<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Stringable;
use function Milebits\Helpers\Helpers\staticPropVal;

/**
 * Trait LaraFilters
 *
 * @mixin Model
 */
trait Filterable
{
    /**
     * @param array|string[]|Stringable[]|string|Stringable|null $filters
     * @param bool $only
     * @param Closure|null $then
     * @return Builder
     */
    public static function filtered($filters = [], bool $only = false, Closure $then = null): Builder
    {
        $result = app(Pipeline::class)->send(static::query())
            ->through(
                $only
                    ? (array)$filters ?? []
                    : array_merge((array)staticPropVal(self::class, "filters", []), (array)$filters)
            );
        return $result->then($then ?? fn($passable) => $passable);
    }
}